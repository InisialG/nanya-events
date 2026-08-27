<?php

namespace App\Filament\Resources\SeatMasterResource\Pages;

use App\Filament\Resources\SeatMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeatMasters extends ListRecords
{
    protected static string $resource = SeatMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
