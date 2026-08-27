<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeatCategoryResource\Pages;
use App\Models\SeatCategory;
use Filament\Actions;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SeatCategoryResource extends Resource
{
    protected static ?string $model = SeatCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|\UnitEnum|null $navigationGroup = 'Master Venue & Denah';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Kategori & Harga Kursi';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Detail Kategori Kursi & Harga')
                    ->description('Harga yang ditetapkan di sini otomatis berlaku untuk seluruh event & sesi yang diselenggarakan di venue ini.')
                    ->schema([
                        Components\Select::make('venue_id')
                            ->relationship('venue', 'name')
                            ->label('Venue')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Components\TextInput::make('name')
                            ->label('Nama Kategori Kursi')
                            ->placeholder('contoh: VIP Front, Reguler Zone A, Balkon')
                            ->required()
                            ->maxLength(255),

                        Components\ColorPicker::make('color_code')
                            ->label('Warna Visual Denah')
                            ->default('#3B82F6')
                            ->required(),

                        Components\TextInput::make('price')
                            ->label('Harga Tetap (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(100000)
                            ->required(),
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

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\ColorColumn::make('color_code')
                    ->label('Warna Denah'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Harga Tetap')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('seat_masters_count')
                    ->label('Jumlah Kursi')
                    ->counts('seatMasters')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('venue_id')
                    ->relationship('venue', 'name')
                    ->label('Filter Venue'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
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
            'index' => Pages\ListSeatCategories::route('/'),
            'create' => Pages\CreateSeatCategory::route('/create'),
            'edit' => Pages\EditSeatCategory::route('/{record}/edit'),
        ];
    }
}
