<?php

namespace App\Jobs;

use App\Models\SeatAvailability;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReleaseExpiredSeatsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        return; // DIBEKUKAN SEMENTARA: Jangan lepas kursi secara otomatis
        
        SeatAvailability::where('status', 'locked')
            ->whereNull('order_id')
            ->where('locked_until', '<', now())
            ->update([
                'status' => 'available',
                'user_id' => null,
                'locked_until' => null,
            ]);
    }
}
