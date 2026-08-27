<?php

namespace App\Observers;

use App\Models\EventSession;
use App\Models\SeatAvailability;
use App\Models\SeatMaster;
use Illuminate\Support\Facades\DB;

class EventSessionObserver
{
    /**
     * Handle the EventSession "created" event.
     * Otomatis membuat ketersediaan kursi ('available') per sesi dari denah venue.
     */
    public function created(EventSession $eventSession): void
    {
        $event = $eventSession->event;
        if (!$event) {
            return;
        }

        // Ambil seluruh kursi aktif di venue yang bersangkutan
        $seatMasters = SeatMaster::where('venue_id', $event->venue_id)
            ->where('is_active', true)
            ->get();

        if ($seatMasters->isEmpty()) {
            return;
        }

        $now = now();
        $recordsToInsert = [];

        foreach ($seatMasters as $seatMaster) {
            $recordsToInsert[] = [
                'event_session_id' => $eventSession->id,
                'seat_master_id' => $seatMaster->id,
                'order_id' => null,
                'status' => 'available',
                'locked_until' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Bulk insert dalam chunk 500 records untuk performa tinggi
        DB::transaction(function () use ($recordsToInsert) {
            foreach (array_chunk($recordsToInsert, 500) as $chunk) {
                SeatAvailability::insert($chunk);
            }
        });
    }
}
