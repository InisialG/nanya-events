<?php

namespace Database\Seeders;

use App\Models\SeatCategory;
use App\Models\SeatMaster;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds for the exact venue seat map.
     */
    public function run(): void
    {
        // 1. Master Venue Utama
        $venue = Venue::firstOrCreate(
            ['name' => 'Auditorium Sailendra Lt. 3'],
            [
                'address' => 'Vihara Borobudur, Jl. Imam Bonjol No. 21 Medan',
                'total_rows' => 17,
                'total_columns' => 38,
                'is_active' => true,
            ]
        );

        // Reset existing seats & categories if re-running
        SeatMaster::where('venue_id', $venue->id)->delete();
        SeatCategory::where('venue_id', $venue->id)->delete();

        // 2. Buat Kategori Kursi & Harga sesuai Gambar
        $diamondCategory = SeatCategory::create([
            'venue_id' => $venue->id,
            'name' => 'DIAMOND',
            'color_code' => '#00C4DF', // Cyan / Light Blue
            'price' => 275000,
        ]);

        $goldCategory = SeatCategory::create([
            'venue_id' => $venue->id,
            'name' => 'GOLD',
            'color_code' => '#F59E0B', // Amber / Gold Yellow
            'price' => 225000,
        ]);

        $pinkCategory = SeatCategory::create([
            'venue_id' => $venue->id,
            'name' => 'PINK',
            'color_code' => '#EC4899', // Hot Pink
            'price' => 150000,
        ]);

        // 3. Generate Bulk Grid Seats
        $seatsData = [];
        $now = now();

        // Map Diagonal Pink Seats
        $pinkSeatsMap = [
            3 => [7, 8, 31, 32],                       // Row C
            4 => [5, 6, 7, 8, 31, 32, 33, 34],         // Row D
            5 => [4, 5, 6, 7, 32, 33, 34, 35],         // Row E
            6 => [3, 4, 5, 6, 33, 34, 35, 36],         // Row F
            7 => [1, 2, 3, 4, 5, 34, 35, 36, 37, 38],  // Row G
            8 => [1, 2, 3, 4, 35, 36, 37, 38],         // Row H
        ];

        // Red Pillars / Obstacles
        $pillarSeats = [
            '2-11', '2-28',   // Row B corners
            '10-11', '10-28', // Row K corners
            '16-11', '16-28', // Row S corners
        ];

        for ($row = 1; $row <= 17; $row++) {
            $rowLetter = SeatMaster::rowNumToLetter($row);

            for ($col = 1; $col <= 38; $col++) {
                $isAisle = ($col === 9 || $col === 10 || $col === 29 || $col === 30);
                $isStageCenterEmpty = ($row === 1 && $col >= 11 && $col <= 28);
                $isPillar = in_array("{$row}-{$col}", $pillarSeats);

                $isActive = !($isAisle || $isStageCenterEmpty || $isPillar);

                // Tentukan Kategori Kursi
                $categoryId = $goldCategory->id;

                if ($col >= 11 && $col <= 28) {
                    // Center Block -> DIAMOND
                    $categoryId = $diamondCategory->id;
                } else {
                    // Wings -> GOLD, check if in PINK map
                    if (isset($pinkSeatsMap[$row]) && in_array($col, $pinkSeatsMap[$row])) {
                        $categoryId = $pinkCategory->id;
                    }
                }

                $seatCode = $isActive ? "{$rowLetter}-{$col}" : "GAP-{$row}-{$col}";

                $seatsData[] = [
                    'venue_id' => $venue->id,
                    'seat_category_id' => $categoryId,
                    'seat_code' => $seatCode,
                    'row_num' => $row,
                    'col_num' => $col,
                    'is_active' => $isActive,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($seatsData, 500) as $chunk) {
            SeatMaster::insert($chunk);
        }
    }
}
