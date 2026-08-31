<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\SeatAvailability;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CancelExpiredOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $expiredOrders = Order::where('status', 'pending_payment')
            ->where('expired_at', '<', now())
            ->get();

        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                $order->update(['status' => 'cancelled']);

                SeatAvailability::where('order_id', $order->id)
                    ->update([
                        'status' => 'available',
                        'order_id' => null,
                        'locked_until' => null,
                    ]);
            });
        }
    }
}
