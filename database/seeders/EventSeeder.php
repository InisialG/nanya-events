<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\Event;
use App\Models\EventSession;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $venue = Venue::first();
        $admin = User::first();

        if (!$venue || !$admin) {
            return;
        }

        // 1. Buat Event Published "Shine in Harmony" (Mid-Autumn Festival)
        $event = Event::firstOrCreate(
            ['slug' => 'shine-in-harmony'],
            [
                'venue_id' => $venue->id,
                'created_by' => $admin->id,
                'title' => 'Shine in Harmony',
                'description' => '<p>Celebrating a cherished tradition and honoring the rich cultural tapestry of the Mid-Autumn Festival through music, dance, and verse.</p><p><strong>Penyelenggara:</strong> Nanyang Zhi Hui Modern Indonesian School</p><p><strong>Syarat & Ketentuan:</strong></p><ul><li>Penonton wajib hadir 30 menit sebelum sesi pertunjukan dimulai.</li><li>E-tiket ber-QR code wajib ditunjukkan kepada petugas pintu masuk venue.</li><li>Dilarang membawa makanan dan minuman dari luar ke dalam Auditorium.</li></ul>',
                'event_category' => 'Pertunjukan',
                'payment_verification_timeout_hours' => 24,
                'status' => 'published',
            ]
        );

        // 2. Buat Sesi Pertunjukan Sabtu, 26 September 2026 (15:00 - 17:30 WIB)
        EventSession::firstOrCreate(
            ['event_id' => $event->id, 'session_date' => '2026-09-26', 'start_time' => '15:00:00'],
            ['end_time' => '17:30:00']
        );

        // 3. Buat Rekening Bank Tujuan Transfer Demo
        BankAccount::firstOrCreate(
            ['account_number' => '8830-1928-33'],
            [
                'bank_name' => 'BCA',
                'account_holder' => 'Nanyang Zhi Hui School',
                'is_active' => true,
            ]
        );

        BankAccount::firstOrCreate(
            ['account_number' => '137-00-99201-882'],
            [
                'bank_name' => 'Mandiri',
                'account_holder' => 'Nanyang Zhi Hui School',
                'is_active' => true,
            ]
        );
    }
}
