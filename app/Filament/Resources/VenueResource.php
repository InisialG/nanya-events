<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenueResource\Pages;
use App\Models\Venue;
use App\Services\SeatGeneratorService;
use Filament\Actions;
use Filament\Forms\Components;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Venue & Denah';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Venue')
                    ->schema([
                        Components\TextInput::make('name')
                            ->label('Nama Venue')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('contoh: Gedung Kesenian Jakarta'),

                        Components\Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(1),

                Section::make('Konfigurasi Grid Denah Kursi')
                    ->description('Tentukan ukuran grid baris dan kolom untuk venue ini.')
                    ->schema([
                        Components\TextInput::make('total_rows')
                            ->label('Jumlah Baris Kursi')
                            ->numeric()
                            ->default(10)
                            ->required()
                            ->minValue(1)
                            ->maxValue(100)
                            ->helperText('Baris akan otomatis diberi nama A, B, C, dst.'),

                        Components\TextInput::make('total_columns')
                            ->label('Jumlah Kolom Kursi')
                            ->numeric()
                            ->default(12)
                            ->required()
                            ->minValue(1)
                            ->maxValue(100)
                            ->helperText('Jumlah kursi per baris (1, 2, 3...).'),

                        Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Venue')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('total_rows')
                    ->label('Baris')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_columns')
                    ->label('Kolom')
                    ->sortable(),

                Tables\Columns\TextColumn::make('seat_masters_count')
                    ->label('Total Kursi Physical')
                    ->counts('seatMasters')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Actions\Action::make('generateGrid')
                    ->label('Generate Grid Kursi')
                    ->icon('heroicon-o-squares-plus')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Denah Kursi Otomatis')
                    ->modalDescription('Sistem akan membuat kombinasi fisik kursi sesuai jumlah baris dan kolom yang ditentukan. Kursi yang sudah ada tidak akan terduplikasi.')
                    ->action(function (Venue $record) {
                        $count = SeatGeneratorService::generateForVenue($record);
                        Notification::make()
                            ->title('Grid Kursi Berhasil Di-generate!')
                            ->body("Berhasil menambahkan {$count} data kursi baru ke master denah.")
                            ->success()
                            ->send();
                    }),

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit' => Pages\EditVenue::route('/{record}/edit'),
        ];
    }
}
