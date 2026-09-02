<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\SeatAvailability;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;
    
    public array $tempSelectedSeats = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['selected_seat_ids'] = SeatAvailability::where('order_id', $data['id'])->pluck('id')->toArray();
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['selected_seat_ids'])) {
            $this->tempSelectedSeats = $data['selected_seat_ids'];
            
            if (count($this->tempSelectedSeats) > 0) {
                $seats = SeatAvailability::with('seatMaster.seatCategory')->whereIn('id', $this->tempSelectedSeats)->get();
                $totalAmount = 0;
                foreach ($seats as $seat) {
                    $totalAmount += $seat->seatMaster?->seatCategory?->price ?? 0;
                }
                $data['total_amount'] = $totalAmount;
                $data['final_amount'] = $totalAmount;
            }
            
            unset($data['selected_seat_ids']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $order = $this->record;
        
        if (!in_array($order->status, ['pending_payment', 'waiting_verification'])) {
            return;
        }

        $selectedSeatIds = $this->tempSelectedSeats;
        if (empty($selectedSeatIds)) {
            return;
        }

        $currentSeatIds = SeatAvailability::where('order_id', $order->id)->pluck('id')->toArray();

        $seatsToAdd = array_diff($selectedSeatIds, $currentSeatIds);
        $seatsToRemove = array_diff($currentSeatIds, $selectedSeatIds);

        if (count($seatsToRemove) > 0) {
            SeatAvailability::whereIn('id', $seatsToRemove)->update([
                'order_id' => null,
                'status' => 'available',
                'locked_until' => null,
            ]);
        }

        if (count($seatsToAdd) > 0) {
            SeatAvailability::whereIn('id', $seatsToAdd)->update([
                'order_id' => $order->id,
                'status' => 'locked',
                'locked_until' => $order->expired_at,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
