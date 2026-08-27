<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers\EventSessionsRelationManager;
use App\Models\Event;
use Filament\Actions;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Event';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Daftar Event Pentas Seni';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Utama Event')
                    ->columnSpanFull()
                    ->schema([
                        Components\TextInput::make('title')
                            ->label('Judul Event')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('contoh: Malam Pertunjukan Teater Mahakarya 2026'),

                        Components\Select::make('venue_id')
                            ->relationship('venue', 'name')
                            ->label('Lokasi / Venue')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Components\Select::make('event_category')
                            ->label('Kategori Acara')
                            ->options([
                                'Pertunjukan' => 'Pertunjukan (Teater, Tari, Konser)',
                            ])
                            ->default('Pertunjukan')
                            ->required(),

                        Components\TextInput::make('payment_verification_timeout_hours')
                            ->label('Batas Jam Verifikasi Pembayaran')
                            ->numeric()
                            ->default(24)
                            ->suffix('Jam')
                            ->required()
                            ->helperText('Durasi maksimal kursi terkunci menunggu verifikasi bukti transfer admin sebelum dibatalkan otomatis.'),

                        Components\Select::make('status')
                            ->label('Status Event')
                            ->options([
                                'coming_soon' => 'Coming Soon (Akan Datang)',
                                'registration' => 'Registrasi / Pendaftaran Dibuka',
                                'ongoing' => 'Berjalan / Sedang Berlangsung',
                                'finished' => 'Selesai',
                                'draft' => 'Draft (Belum Tayang)',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->default('registration')
                            ->required(),

                        Components\ViewField::make('poster_path')
                            ->view('components.cloudinary-upload')
                            ->columnSpanFull(),

                        Components\RichEditor::make('description')
                            ->label('Deskripsi Lengkap & Syarat Ketentuan')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('poster_path')
                    ->label('Poster')
                    ->square()
                    ->checkFileExistence(false)
                    ->state(function (Event $record) {
                        if (blank($record->poster_path)) {
                            return null;
                        }
                        return str_starts_with($record->poster_path, 'http')
                            ? $record->poster_path
                            : asset('storage/' . $record->poster_path);
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Event')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('venue.name')
                    ->label('Venue')
                    ->sortable(),

                Tables\Columns\TextColumn::make('event_sessions_count')
                    ->label('Total Sesi')
                    ->counts('eventSessions')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('payment_verification_timeout_hours')
                    ->label('Timeout Bayar')
                    ->suffix(' Jam'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'coming_soon' => 'info',
                        'registration' => 'success',
                        'ongoing' => 'warning',
                        'finished' => 'gray',
                        'published' => 'success',
                        'draft' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'coming_soon' => 'Coming Soon',
                        'registration' => 'Registrasi',
                        'ongoing' => 'Berjalan',
                        'finished' => 'Selesai',
                        'published' => 'Registrasi',
                        'draft' => 'Draft',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'coming_soon' => 'Coming Soon',
                        'registration' => 'Registrasi',
                        'ongoing' => 'Berjalan',
                        'finished' => 'Selesai',
                        'draft' => 'Draft',
                        'cancelled' => 'Dibatalkan',
                    ]),
                Tables\Filters\SelectFilter::make('venue_id')
                    ->relationship('venue', 'name')
                    ->label('Venue'),
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EventSessionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
