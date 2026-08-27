<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'color_code',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function seatMasters(): HasMany
    {
        return $this->hasMany(SeatMaster::class);
    }
}
