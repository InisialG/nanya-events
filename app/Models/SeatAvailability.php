<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_session_id',
        'seat_master_id',
        'order_id',
        'status',
        'locked_until',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
    ];

    public function eventSession(): BelongsTo
    {
        return $this->belongsTo(EventSession::class);
    }

    public function seatMaster(): BelongsTo
    {
        return $this->belongsTo(SeatMaster::class);
    }

    public function ticket(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Ticket::class, 'seat_availability_id');
    }
}
