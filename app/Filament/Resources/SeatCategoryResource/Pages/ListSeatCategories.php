<?php

namespace App\Filament\Resources\SeatCategoryResource\Pages;

use App\Filament\Resources\SeatCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSeatCategories extends ListRecords
{
    protected static string $resource = SeatCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
