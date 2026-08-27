<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeatMasterResource\Pages;
use App\Models\SeatCategory;
use App\Models\SeatMaster;
use Filament\Actions;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SeatMasterResource extends Resource
{
    protected static ?string $model = SeatMaster::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Venue & Denah';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Master Kursi Physical';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Kursi Physical')
                    ->schema([
                        Components\Select::make('venue_id')
                            ->relationship('venue', 'name')
                            ->label('Venue')
                            ->required()
                            ->reactive(),

                        Components\Select::make('seat_category_id')
                            ->options(function (callable $get) {
                                $venueId = $get('venue_id');
                                if (!$venueId) {
                                    return [];
                                }
                                return SeatCategory::where('venue_id', $venueId)->pluck('name', 'id');
                            })
                            ->label('Kategori Kursi')
                            ->searchable()
                            ->nullable(),

                        Components\TextInput::make('seat_code')
                            ->label('Kode Kursi (mis. A-12)')
                            ->required()
                            ->maxLength(50),

                        Components\TextInput::make('row_num')
                            ->label('Index Baris')
                            ->numeric()
                            ->required(),

                        Components\TextInput::make('col_num')
                            ->label('Index Kolom')
                            ->numeric()
                            ->required(),

                        Components\Toggle::make('is_active')
                            ->label('Kursi Aktif (Bisa Di-book)')
                            ->helperText('Matikan toggle ini jika lokasi kursi tertutup tiang/dijadikan gang (aisle).')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('venue.name')
                    ->label('Venue')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('seat_code')
                    ->label('Kode Kursi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('seatCategory.name')
                    ->label('Kategori Kursi')
                    ->badge()
                    ->color('primary')
                    ->placeholder('Tanpa Kategori'),

                Tables\Columns\TextColumn::make('seatCategory.price')
                    ->label('Harga Tetap')
                    ->money('IDR', locale: 'id')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('row_num')
                    ->label('Baris #')
                    ->sortable(),

                Tables\Columns\TextColumn::make('col_num')
                    ->label('Kolom #')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('seat_code', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('venue_id')
                    ->relationship('venue', 'name')
                    ->label('Filter Venue'),

                Tables\Filters\SelectFilter::make('seat_category_id')
                    ->relationship('seatCategory', 'name')
                    ->label('Filter Kategori'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeatMasters::route('/'),
        ];
    }
}
