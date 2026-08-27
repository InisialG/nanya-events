<?php

namespace App\Filament\Resources\SeatCategoryResource\Pages;

use App\Filament\Resources\SeatCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSeatCategory extends EditRecord
{
    protected static string $resource = SeatCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
