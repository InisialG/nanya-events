<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'total_rows',
        'total_columns',
        'is_active',
    ];

    protected $casts = [
        'total_rows' => 'integer',
        'total_columns' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Kategori kursi yang terdaftar pada venue ini.
     */
    public function seatCategories(): HasMany
    {
        return $this->hasMany(SeatCategory::class);
    }

    /**
     * Master denah kursi fisik yang dimiliki venue ini.
     */
    public function seatMasters(): HasMany
    {
        return $this->hasMany(SeatMaster::class);
    }
}
