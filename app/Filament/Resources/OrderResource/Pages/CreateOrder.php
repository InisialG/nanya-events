<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['order_code'] = 'NYA-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $data['unique_code'] = 0;
        $data['final_amount'] = $data['total_amount'] ?? 0;
        $data['expired_at'] = now()->addHours(24);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
