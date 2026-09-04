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
                'total_rows' => 17, // A-T omitting I,O,Q
                'total_columns' => 41, // Max number in the left zone
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

        // 3. Definitions
        $now = now();
        $seatsData = [];

        // Define Row Mapping (1=A, 2=B, 3=C, 4=D, 5=E, 6=F, 7=G, 8=H, 9=J, 10=K, 11=L, 12=M, 13=N, 14=P, 15=R, 16=S, 17=T)
        $rowLetters = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'J' => 9, 'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'P' => 14, 'R' => 15, 'S' => 16, 'T' => 17];

        $zoneRanges = [
            'L' => [ // Zona Kiri
                'A' => [8, 17], 'B' => [27, 36], 'C' => [26, 36], 'D' => [29, 39], 'E' => [30, 40],
                'F' => [30, 40], 'G' => [31, 40], 'H' => [31, 40], 'J' => [25, 32], 'K' => [24, 32],
                'L' => [27, 35], 'M' => [28, 36], 'N' => [28, 36], 'P' => [31, 41], 'R' => [31, 41],
                'S' => [27, 33], 'T' => [27, 33],
            ],
            'C' => [ // Zona Tengah
                'B' => [8, 26], 'C' => [8, 25], 'D' => [8, 28], 'E' => [8, 29], 'F' => [8, 29],
                'G' => [8, 30], 'H' => [7, 30], 'J' => [6, 24], 'K' => [6, 23], 'L' => [6, 26],
                'M' => [6, 27], 'N' => [6, 27], 'P' => [8, 30], 'R' => [8, 30], 'S' => [8, 26],
                'T' => [8, 26],
            ],
            'R' => [ // Zona Kanan
                'A' => [1, 7], 'B' => [1, 7], 'C' => [1, 7], 'D' => [1, 7], 'E' => [1, 7],
                'F' => [1, 7], 'G' => [1, 7], 'H' => [1, 6], 'J' => [1, 5], 'K' => [1, 5],
                'L' => [1, 5], 'M' => [1, 5], 'N' => [1, 5], 'P' => [1, 7], 'R' => [1, 7],
                'S' => [1, 7], 'T' => [1, 7],
            ]
        ];

        // Daftar Kursi Pink
        $pinkSeats = [
            'R-C05', 'R-C06', 'R-C07', 'L-C26', 'L-C27',
            'R-D04', 'R-D05', 'R-D06', 'R-D07', 'L-D29', 'L-D30', 'L-D31', 'L-D32', 'L-D33',
            'R-E01', 'R-E02', 'R-E03', 'R-E04', 'R-E05', 'R-E06', 'L-E32', 'L-E33', 'L-E34', 'L-E35',
            'R-F01', 'R-F02', 'R-F03', 'R-F04', 'R-F05', 'L-F33', 'L-F34', 'L-F35', 'L-F36', 'L-F37',
            'R-G01', 'R-G02', 'R-G03', 'R-G04', 'L-G35', 'L-G36', 'L-G37', 'L-G38', 'L-G39', 'L-G40',
            'R-H01', 'R-H02', 'L-H36', 'L-H37', 'L-H38', 'L-H39', 'L-H40'
        ];

        foreach ($zoneRanges as $zone => $rows) {
            foreach ($rows as $rowLetter => $range) {
                $start = $range[0];
                $end = $range[1];
                $rowNum = $rowLetters[$rowLetter];

                for ($num = $start; $num <= $end; $num++) {
                    $formattedNum = str_pad($num, 2, '0', STR_PAD_LEFT);
                    $seatCode = "{$zone}-{$rowLetter}{$formattedNum}";

                    // Tentukan package
                    $categoryId = $goldCategory->id;
                    if ($zone === 'C') {
                        $categoryId = $diamondCategory->id;
                    } elseif (in_array($seatCode, $pinkSeats)) {
                        $categoryId = $pinkCategory->id;
                    }

                    $seatsData[] = [
                        'venue_id' => $venue->id,
                        'seat_category_id' => $categoryId,
                        'seat_code' => $seatCode,
                        'row_num' => $rowNum, // Used for internal sorting if needed
                        'col_num' => $num, // The actual seat number
                        'is_active' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        foreach (array_chunk($seatsData, 500) as $chunk) {
            SeatMaster::insert($chunk);
        }
    }
}
