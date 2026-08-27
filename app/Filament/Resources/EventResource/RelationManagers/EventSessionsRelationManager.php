<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use Filament\Actions;
use Filament\Forms\Components;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class EventSessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'eventSessions';

    protected static ?string $title = 'Jadwal / Sesi Pertunjukan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Components\DatePicker::make('session_date')
                    ->label('Tanggal Sesi')
                    ->required()
                    ->default(now()),

                Components\TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->required()
                    ->default('19:00'),

                Components\TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->default('22:00'),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('session_date')
            ->columns([
                Tables\Columns\TextColumn::make('session_date')
                    ->label('Tanggal Sesi')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Jam Mulai'),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('Jam Selesai')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('seat_availabilities_count')
                    ->label('Status Kursi Generated')
                    ->counts('seatAvailabilities')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Tambah Sesi Acara'),
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
}
