<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\SeatAvailability;
use App\Models\Ticket;
use Filament\Actions;
use Filament\Forms\Components;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Transaksi';

    protected static ?string $navigationLabel = 'Verifikasi Pembayaran';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'waiting_verification')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pesanan')
                    ->components([
                        Components\TextInput::make('order_code')
                            ->label('Kode Order')
                            ->placeholder('Otomatis dibuat sistem (NYA-...)')
                            ->disabled()
                            ->visible(fn (?Order $record) => $record !== null),

                        Components\Select::make('user_id')
                            ->label('Penonton / Akun Pemesan')
                            ->options(\App\Models\User::pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->disabled(fn (?Order $record) => $record !== null),

                        Components\Select::make('event_session_id')
                            ->label('Sesi Event Pertunjukan')
                            ->options(function () {
                                return \App\Models\EventSession::with('event')->get()->mapWithKeys(function ($session) {
                                    $date = \Carbon\Carbon::parse($session->session_date)->translatedFormat('d M Y');
                                    $time = \Carbon\Carbon::parse($session->start_time)->format('H:i');
                                    return [$session->id => "{$session->event?->title} — {$date} ({$time} WIB)"];
                                });
                            })
                            ->searchable()
                            ->required()
                            ->disabled(fn (?Order $record) => $record !== null),

                        Components\Select::make('bank_account_id')
                            ->label('Bank Tujuan Transfer')
                            ->options(function () {
                                return \App\Models\BankAccount::where('is_active', true)->get()->mapWithKeys(function ($bank) {
                                    return [$bank->id => "{$bank->bank_name} ({$bank->account_number} a/n {$bank->account_holder})"];
                                });
                            })
                            ->required()
                            ->disabled(fn (?Order $record) => $record !== null),

                        Components\TextInput::make('total_amount')
                            ->label('Total Nominal Pembayaran (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->disabled(fn (?Order $record) => $record !== null),

                        Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->options([
                                'pending_payment' => 'Menunggu Pembayaran',
                                'waiting_verification' => 'Menunggu Verifikasi',
                                'paid' => 'Lunas / Disetujui',
                                'rejected' => 'Ditolak',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('waiting_verification')
                            ->disabled()
                            ->dehydrated(false)
                            ->required(),

                        Components\Select::make('selected_seat_ids')
                            ->label('Ubah Kursi Dipesan (Hanya muncul jika pesanan belum lunas/dibatalkan)')
                            ->multiple()
                            ->searchable()
                            ->required()
                            ->options(function (string $operation, ?Order $record) {
                                if (!$record) return [];
                                return \App\Models\SeatAvailability::with('seatMaster.seatCategory')
                                    ->where('event_session_id', $record->event_session_id)
                                    ->where(function($q) use ($record) {
                                        $q->where('status', 'available')
                                          ->orWhere('order_id', $record->id);
                                    })
                                    ->get()
                                    ->mapWithKeys(function($seat) {
                                        $catName = $seat->seatMaster?->seatCategory?->name ?? 'Unknown';
                                        $price = number_format($seat->seatMaster?->seatCategory?->price ?? 0, 0, ',', '.');
                                        $code = $seat->seatMaster?->seat_code ?? '-';
                                        return [$seat->id => "{$code} - {$catName} (Rp {$price})"];
                                    });
                            })
                            ->visible(fn (string $operation, ?Order $record) => $operation === 'edit' && in_array($record?->status, ['pending_payment', 'waiting_verification']))
                            ->columnSpanFull(),

                        Components\Placeholder::make('seats_detail')
                            ->label('Daftar Kursi Dipesan')
                            ->visible(fn (string $operation, ?Order $record) => $record !== null && ($operation !== 'edit' || in_array($record->status, ['paid', 'rejected', 'cancelled'])))
                            ->content(function (?Order $record) {
                                if (!$record) return '-';
                                $seats = $record->seatAvailabilities()->with('seatMaster.seatCategory')->get();
                                if ($seats->isEmpty()) return new \Illuminate\Support\HtmlString('<span class="text-gray-500 italic">Tidak ada kursi</span>');
                                
                                // Kelompokkan berdasarkan kategori
                                $grouped = [];
                                foreach ($seats as $seat) {
                                    $catName = $seat->seatMaster?->seatCategory?->name ?? 'Unknown';
                                    $catColor = $seat->seatMaster?->seatCategory?->color_hex ?? '#333';
                                    $seatCode = $seat->seatMaster?->seat_code ?? '-';
                                    
                                    if (!isset($grouped[$catName])) {
                                        $grouped[$catName] = [
                                            'color' => $catColor,
                                            'seats' => []
                                        ];
                                    }
                                    $grouped[$catName]['seats'][] = $seatCode;
                                }

                                $html = '<div style="display: flex; flex-direction: column; gap: 16px; margin-top: 4px;">';
                                foreach ($grouped as $catName => $data) {
                                    $html .= '<div>';
                                    
                                    // Header Kategori
                                    $html .= '<div style="font-weight: 700; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; font-size: 14px;">';
                                    $html .= '<span style="display: inline-block; width: 12px; height: 12px; border-radius: 50%; background-color: '.$data['color'].'; box-shadow: 0 1px 2px rgba(0,0,0,0.2);"></span>';
                                    $html .= $catName . ' <span style="font-weight: normal; color: #6b7280; font-size: 12px;">(' . count($data['seats']) . ' kursi)</span>';
                                    $html .= '</div>';
                                    
                                    // Daftar Kursi
                                    $html .= '<div style="display: flex; flex-wrap: wrap; gap: 6px;">';
                                    foreach ($data['seats'] as $code) {
                                        $html .= '<span style="background-color: #f8fafc; border: 1px solid #cbd5e1; color: #334155; padding: 2px 10px; border-radius: 6px; font-size: 13px; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">' . $code . '</span>';
                                    }
                                    $html .= '</div>';
                                    
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan Waktu & Batas Kedaluwarsa')
                    ->components([
                        Components\DateTimePicker::make('created_at')
                            ->label('Waktu Dibuat (Created At)'),
                        Components\DateTimePicker::make('updated_at')
                            ->label('Waktu Diperbarui (Updated At)'),
                        Components\DateTimePicker::make('expired_at')
                            ->label('Batas Waktu Pembayaran (Expired At)'),
                    ])
                    ->columns(3),

                Section::make('Bukti Pembayaran / Transfer Bank')
                    ->visible(fn (?Order $record) => $record !== null)
                    ->components([
                        Components\Placeholder::make('sender_bank')
                            ->label('Nama Bank Pengirim')
                            ->content(fn (?Order $record) => $record?->payment?->sender_bank ?? 'Belum diisi'),

                        Components\Placeholder::make('sender_name')
                            ->label('Nama Pengirim di Struk')
                            ->content(fn (?Order $record) => $record?->payment?->sender_name ?? 'Belum diisi'),

                        Components\Placeholder::make('uploaded_at')
                            ->label('Waktu Upload Struk')
                            ->content(fn (?Order $record) => $record?->payment?->uploaded_at ? \Carbon\Carbon::parse($record->payment->uploaded_at)->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum upload'),

                        Components\Placeholder::make('proof_image')
                            ->label('Foto / Dokumen Bukti Transfer')
                            ->content(function (?Order $record) {
                                if (!$record?->payment?->proof_path) {
                                    return new \Illuminate\Support\HtmlString('<span class="text-amber-400 font-semibold italic">⚠️ Belum ada foto/file bukti transfer yang diunggah oleh penonton.</span>');
                                }
                                
                                $path = $record->payment->proof_path;
                                $url = str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
                                $isPdf = str_ends_with(strtolower($record->payment->proof_path), '.pdf');

                                if ($isPdf) {
                                    return new \Illuminate\Support\HtmlString('
                                        <div class="py-2">
                                            <a href="' . $url . '" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs transition-all shadow-lg">
                                                📄 Buka Dokumen Bukti Transfer (PDF) →
                                            </a>
                                        </div>
                                    ');
                                }

                                return new \Illuminate\Support\HtmlString('
                                    <div class="space-y-2 pt-1">
                                        <div class="inline-block rounded-xl overflow-hidden border border-slate-700 shadow-md bg-slate-950 p-1.5">
                                            <a href="' . $url . '" target="_blank" title="Klik untuk membuka foto ukuran penuh">
                                                <img src="' . $url . '" alt="Bukti Transfer" class="w-48 h-48 object-cover rounded-lg hover:scale-105 transition-transform cursor-zoom-in">
                                            </a>
                                        </div>
                                        <div>
                                            <a href="' . $url . '" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-emerald-400 hover:text-emerald-300 font-extrabold hover:underline">
                                                <span>🔍 Buka Foto Ukuran Penuh (Tab Baru)</span> →
                                            </a>
                                        </div>
                                    </div>
                                ');
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Kode Order')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penonton')
                    ->searchable(),

                Tables\Columns\TextColumn::make('eventSession.event.title')
                    ->label('Event')
                    ->searchable()
                    ->limit(15)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('final_amount')
                    ->label('Total Bayar')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('bankAccount.bank_name')
                    ->label('Bank')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending_payment' => 'warning',
                        'waiting_verification' => 'info',
                        'paid' => 'success',
                        'rejected' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending_payment' => 'Pending Bayar',
                        'waiting_verification' => 'Perlu Verifikasi',
                        'paid' => 'Lunas (E-Tiket)',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Pesan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'waiting_verification' => 'Menunggu Verifikasi',
                        'pending_payment' => 'Menunggu Pembayaran',
                        'paid' => 'Lunas / Disetujui',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                Actions\ActionGroup::make([
                    // Action 1: Lihat Bukti Transfer Modal (Compact & Neat)
                    Actions\Action::make('viewProof')
                        ->label('Lihat Bukti Transfer')
                        ->icon('heroicon-o-photo')
                        ->color('info')
                        ->modalWidth('md')
                        ->modalHeading('Bukti Transfer Pembayaran')
                        ->modalContent(function (Order $record) {
                            if (!$record->payment?->proof_path) {
                                return new \Illuminate\Support\HtmlString('<div class="p-4 text-center text-slate-400">Belum ada foto/file bukti transfer yang diunggah untuk order ini.</div>');
                            }
                            $path = $record->payment->proof_path;
                            $url = str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
                            $isPdf = str_ends_with(strtolower($record->payment->proof_path), '.pdf');

                            if ($isPdf) {
                                return new \Illuminate\Support\HtmlString('
                                    <div class="p-4 text-center space-y-3">
                                        <p class="text-xs text-slate-300">Bank Pengirim: <strong class="text-white">' . e($record->payment->sender_bank) . '</strong> | Pengirim: <strong class="text-white">' . e($record->payment->sender_name) . '</strong></p>
                                        <a href="' . $url . '" target="_blank" class="inline-block px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg text-xs">
                                            📄 Buka Dokumen Bukti Transfer (PDF)
                                        </a>
                                    </div>
                                ');
                            }

                            return new \Illuminate\Support\HtmlString('
                                <div class="p-3 text-center space-y-3">
                                    <div class="text-xs text-slate-300">
                                        <span>Bank: <strong class="text-white">' . e($record->payment->sender_bank) . '</strong></span> • 
                                        <span>Pengirim: <strong class="text-white">' . e($record->payment->sender_name) . '</strong></span>
                                    </div>
                                    <div class="flex justify-center">
                                        <a href="' . $url . '" target="_blank" title="Klik untuk membuka ukuran penuh">
                                            <img src="' . $url . '" alt="Bukti Transfer" class="max-h-56 max-w-xs rounded-xl border border-slate-700 shadow-md object-contain hover:scale-105 transition-transform cursor-zoom-in">
                                        </a>
                                    </div>
                                    <a href="' . $url . '" target="_blank" class="text-xs text-emerald-400 hover:underline block font-semibold">
                                        🔍 Buka foto ukuran penuh di tab baru →
                                    </a>
                                </div>
                            ');
                        }),

                    // Action 2: Approve Pembayaran
                    Actions\Action::make('approvePayment')
                        ->label('Approve Pembayaran')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Persetujuan Pembayaran')
                        ->modalDescription('Apakah Anda yakin nominal transfer sudah sesuai? E-Tiket ber-QR Code akan diterbitkan dan otomatis dikirim ke email penonton.')
                        ->visible(fn (Order $record): bool => in_array($record->status, ['waiting_verification', 'pending_payment']))
                        ->action(function (Order $record) {
                            DB::transaction(function () use ($record) {
                                $record->update(['status' => 'paid']);

                                // Update status seat availabilities to sold
                                $seatAvails = SeatAvailability::where('order_id', $record->id)->get();

                                foreach ($seatAvails as $seat) {
                                    $seat->update([
                                        'status' => 'sold',
                                        'locked_until' => null,
                                    ]);

                                    // Generate E-Ticket per seat jika belum ada
                                    $existingTicket = Ticket::where('order_id', $record->id)->where('seat_availability_id', $seat->id)->first();

                                    if (!$existingTicket) {
                                        $ticketCode = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
                                        $qrHash = hash('sha256', $ticketCode . '-' . $seat->id . '-' . Str::random(12));

                                        Ticket::create([
                                            'ticket_code' => $ticketCode,
                                            'qr_code_hash' => $qrHash,
                                            'order_id' => $record->id,
                                            'seat_availability_id' => $seat->id,
                                            'status' => 'valid',
                                        ]);
                                    }
                                }

                                if ($record->payment) {
                                    $record->payment->update([
                                        'verified_by' => Auth::id(),
                                        'verified_at' => now(),
                                    ]);
                                }
                            });

                            // OTOMATIS KIRIM EMAIL E-TIKET KE PENONTON
                            try {
                                $record->unsetRelation('tickets');
                                $record->load([
                                    'user',
                                    'eventSession.event.venue',
                                    'tickets.seatAvailability.seatMaster.seatCategory'
                                ]);

                                if ($record->user && $record->user->email) {
                                    $resendApiKey = env('RESEND_API_KEY');

                                    if (!empty($resendApiKey)) {
                                        // 🚀 REST API Resend (Native Laravel Http — TANPA PACKAGE / ZERO COMPOSER)
                                        \Illuminate\Support\Facades\Http::withToken($resendApiKey)->post('https://api.resend.com/emails', [
                                            'from' => env('RESEND_FROM_ADDRESS', 'Nanya Events <onboarding@resend.dev>'),
                                            'to' => [$record->user->email],
                                            'subject' => '🎫 E-Tiket Resmi Nanya Events — Order #' . $record->order_code,
                                            'html' => view('emails.ticket-approved', ['order' => $record])->render(),
                                        ]);
                                    } else {
                                        // Fallback ke Mailer SMTP bawaan jika RESEND_API_KEY kosong
                                        \Illuminate\Support\Facades\Mail::to($record->user->email)->send(new \App\Mail\TicketApprovedMail($record));
                                    }
                                }
                            } catch (\Exception $e) {
                                \Illuminate\Support\Facades\Log::error('Gagal mengirim email E-Tiket ke ' . $record->user?->email . ': ' . $e->getMessage());
                            }

                            Notification::make()
                                ->title('Pembayaran Disetujui!')
                                ->body('Order ' . $record->order_code . ' berhasil disetujui, E-Tiket diterbitkan, & otomatis terkirim ke email penonton (' . $record->user?->email . ').')
                                ->success()
                                ->send();
                        }),

                    // Action 3: Reject Pembayaran
                    Actions\Action::make('rejectPayment')
                        ->label('Tolak Pembayaran')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Components\Textarea::make('rejection_reason')
                                ->label('Alasan Penolakan Bukti Transfer')
                                ->placeholder('contoh: Nominal transfer kurang / Gambar tidak terbaca / Tidak ada mutasi masuk')
                                ->required(),
                        ])
                        ->visible(fn (Order $record): bool => in_array($record->status, ['waiting_verification', 'pending_payment']))
                        ->action(function (Order $record, array $data) {
                            DB::transaction(function () use ($record, $data) {
                                $record->update(['status' => 'rejected']);

                                // Release seats back to available
                                SeatAvailability::where('order_id', $record->id)->update([
                                    'status' => 'available',
                                    'order_id' => null,
                                    'locked_until' => null,
                                ]);

                                if ($record->payment) {
                                    $record->payment->update([
                                        'rejection_reason' => $data['rejection_reason'],
                                        'verified_by' => Auth::id(),
                                        'verified_at' => now(),
                                    ]);
                                }
                            });

                            Notification::make()
                                ->title('Pembayaran Ditolak')
                                ->body('Order ' . $record->order_code . ' ditolak & kursi telah dilepas kembali.')
                                ->warning()
                                ->send();
                        }),

                    Actions\EditAction::make()->label('Ubah Order'),

                    // Action 4: Hapus Order Individual
                    Actions\DeleteAction::make()
                        ->label('Hapus Order Ini')
                        ->icon('heroicon-o-trash')
                        ->before(function (Order $record) {
                            // Lepaskan kursi kembali ke status available sebelum order dihapus
                            SeatAvailability::where('order_id', $record->id)->update([
                                'status' => 'available',
                                'order_id' => null,
                                'locked_until' => null,
                            ]);
                            Ticket::where('order_id', $record->id)->delete();
                        }),
                ])->icon('heroicon-m-ellipsis-vertical')->color('gray')->tooltip('Aksi'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->label('Hapus Semua Order Terpilih')
                        ->before(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                SeatAvailability::where('order_id', $record->id)->update([
                                    'status' => 'available',
                                    'order_id' => null,
                                    'locked_until' => null,
                                ]);
                                Ticket::where('order_id', $record->id)->delete();
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
