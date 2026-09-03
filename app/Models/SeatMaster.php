<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'seat_category_id',
        'seat_code',
        'row_num',
        'col_num',
        'is_active',
    ];

    protected $casts = [
        'row_num' => 'integer',
        'col_num' => 'integer',
        'is_active' => 'boolean',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function seatCategory(): BelongsTo
    {
        return $this->belongsTo(SeatCategory::class);
    }

    /**
     * Helper untuk mengonversi index baris mengikuti standar denah teater (A, B, C, D, E, F, G, H, J, K, L, M, N, P, R, S, T...).
     */
    public static function rowNumToLetter(int $rowNum): string
    {
        $map = [
            1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H',
            9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N', 14 => 'P', 15 => 'R', 16 => 'T', 17 => 'U',
            18 => 'V', 19 => 'W', 20 => 'X', 21 => 'Y', 22 => 'Z'
        ];

        return $map[$rowNum] ?? ('R' . $rowNum);
    }
}
