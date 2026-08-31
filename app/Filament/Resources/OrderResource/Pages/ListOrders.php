<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol 1: Export
            Actions\Action::make('exportExcel')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $orders = \App\Models\Order::with(['user', 'payment', 'seatAvailabilities.seatMaster'])->latest()->get();
                    
                    $filename = 'Laporan_Order_NanyaEvents_' . date('Y-m-d_H-i-s') . '.xls';
                    
                    $headers = [
                        'Content-Type' => 'application/vnd.ms-excel',
                        'Content-Disposition' => "attachment; filename=\"$filename\"",
                        'Pragma' => 'no-cache',
                        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                        'Expires' => '0',
                    ];

                    $callback = function () use ($orders) {
                        echo '<table border="1">';
                        echo '<thead style="background-color: #f3f4f6; font-weight: bold;">';
                        echo '<tr>';
                        echo '<th>No. Order</th>';
                        echo '<th>Kursi Dipesan</th>';
                        echo '<th>Nama Pemesan</th>';
                        echo '<th>Email</th>';
                        echo '<th>No. WhatsApp / HP</th>';
                        echo '<th>Total Pembayaran (Rp)</th>';
                        echo '<th>Status Pesanan</th>';
                        echo '<th>Bank Pengirim</th>';
                        echo '<th>Nama Pengirim</th>';
                        echo '<th>Tanggal Transaksi</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        foreach ($orders as $order) {
                            $statusMap = [
                                'waiting_verification' => 'Menunggu Verifikasi Admin',
                                'pending_payment' => 'Menunggu Pembayaran',
                                'paid' => 'Lunas / Disetujui',
                                'rejected' => 'Ditolak',
                                'cancelled' => 'Dibatalkan',
                            ];

                            $bookedSeats = $order->seatAvailabilities
                                ->map(fn($sa) => $sa->seatMaster?->seat_code)
                                ->filter()
                                ->implode(', ');

                            echo '<tr>';
                            echo '<td>' . $order->order_code . '</td>';
                            echo '<td>' . ($bookedSeats ?: '-') . '</td>';
                            echo '<td>' . ($order->user?->name ?? 'Penonton') . '</td>';
                            echo '<td>' . ($order->user?->email ?? '-') . '</td>';
                            echo '<td>' . ($order->user?->phone_number ?? '-') . '</td>';
                            echo '<td>' . $order->final_amount . '</td>';
                            echo '<td>' . ($statusMap[$order->status] ?? $order->status) . '</td>';
                            echo '<td>' . ($order->payment?->sender_bank ?? '-') . '</td>';
                            echo '<td>' . ($order->payment?->sender_name ?? '-') . '</td>';
                            echo '<td>' . $order->created_at->format('Y-m-d H:i:s') . '</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';
                    };

                    return response()->stream($callback, 200, $headers);
                }),

            // Tombol 2: Reset
            Actions\Action::make('resetAllOrders')
                ->label('Reset')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('⚠️ KOSONGKAN SELURUH TRANSAKSI PESANAN?')
                ->modalDescription('Tindakan ini akan MENGHAPUS SELURUH pesanan, bukti transfer, dan e-tiket yang ada di sistem, serta mengembalikan SELURUH KURSI menjadi TERSEDIA kembali.')
                ->action(function () {
                    \Illuminate\Support\Facades\DB::transaction(function () {
                        \App\Models\Ticket::query()->delete();
                        \App\Models\Payment::query()->delete();
                        \App\Models\Order::query()->delete();
                        \App\Models\SeatAvailability::query()->update([
                            'status' => 'available',
                            'order_id' => null,
                            'locked_until' => null,
                        ]);
                    });

                    \Filament\Notifications\Notification::make()
                        ->title('Seluruh Order Berhasil Dikosongkan!')
                        ->body('Seluruh data transaksi telah dibersihkan dan seluruh kursi di venue kembali TERSEDIA.')
                        ->warning()
                        ->send();
                }),
        ];
    }
}
