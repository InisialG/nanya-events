<?php

namespace App\Filament\Resources\SeatCategoryResource\Pages;

use App\Filament\Resources\SeatCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSeatCategory extends CreateRecord
{
    protected static string $resource = SeatCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
