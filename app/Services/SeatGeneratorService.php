<?php

namespace App\Services;

use App\Models\SeatCategory;
use App\Models\SeatMaster;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;

class SeatGeneratorService
{
    /**
     * Generate grid denah kursi fisik untuk venue tertentu.
     *
     * @param Venue $venue
     * @param SeatCategory|null $defaultCategory Kategori default yang akan dipasang (opsional)
     * @param bool $forceRegenerate Jika true, akan mereset dan generate ulang seluruh kursi
     * @return int Jumlah kursi yang berhasil dibuat
     */
    public static function generateForVenue(Venue $venue, ?SeatCategory $defaultCategory = null, bool $forceRegenerate = false): int
    {
        return DB::transaction(function () use ($venue, $defaultCategory, $forceRegenerate) {
            if ($forceRegenerate) {
                $venue->seatMasters()->delete();
            }

            // Ambil kategori pertama di venue ini jika $defaultCategory null
            if (!$defaultCategory) {
                $defaultCategory = $venue->seatCategories()->first();
            }

            $countCreated = 0;
            $seatsToInsert = [];
            $now = now();

            for ($row = 1; $row <= $venue->total_rows; $row++) {
                $rowLetter = SeatMaster::rowNumToLetter($row);

                for ($col = 1; $col <= $venue->total_columns; $col++) {
                    $seatCode = "{$rowLetter}-{$col}";

                    // Cek apakah kursi sudah pernah ada di DB
                    $exists = SeatMaster::where('venue_id', $venue->id)
                        ->where('seat_code', $seatCode)
                        ->exists();

                    if (!$exists) {
                        $seatsToInsert[] = [
                            'venue_id' => $venue->id,
                            'seat_category_id' => $defaultCategory?->id,
                            'seat_code' => $seatCode,
                            'row_num' => $row,
                            'col_num' => $col,
                            'is_active' => true,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                        $countCreated++;
                    }
                }
            }

            if (!empty($seatsToInsert)) {
                // Insert in chunks of 500 for high performance
                foreach (array_chunk($seatsToInsert, 500) as $chunk) {
                    SeatMaster::insert($chunk);
                }
            }

            return $countCreated;
        });
    }
}
