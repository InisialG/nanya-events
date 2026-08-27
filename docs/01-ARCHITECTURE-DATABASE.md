# 🏗️ Technical Architecture & Database Schema

## 1. Stack Teknologi

| Layer | Teknologi / Library | Keterangan |
|---|---|---|
| **Framework Backend** | Laravel 12 (PHP 8.3+) | Core web application & API engine |
| **Admin & Super Admin Panel** | Filament 4.x | Resource, Form Builder, Table Builder, Action Modals |
| **RBAC / Authorization** | Filament Shield 4.x (`spatie/laravel-permission`) | Role & Permission management |
| **Frontend Publik (User)** | Blade + Livewire / Tailwind CSS | Halaman Katalog, Detail Event, Interactive Seat Map Engine |
| **Autentikasi User** | Laravel Socialite + Native Auth | Google OAuth 2.0 + Login Email/Password dengan Verifikasi Email |
| **Queue / Background Jobs** | Laravel Queue (`database` / `redis`) | Handling Seat Release Timer & Notification Email |
| **Database** | MySQL / MariaDB | Relational Database Engine |
| **File Storage** | Laravel Filesystem (`public`/`s3`) | Upload Poster Event & Bukti Transfer Pembayaran |

---

## 2. Skema Database (Database Schema / ERD)

```
[Venue] 1 ─── N [SeatCategory] 1 ─── N [SeatMaster]
   │                                        │
   │ 1                                      │ 1
   ▼                                        ▼
[Event] 1 ─── N [EventSession] 1 ─── N [SeatAvailability]
   │                 │                      │
   │                 │ 1                    │
   │                 ▼                      │
   └──────────── N [Order] ◄────────────────┘
                     │ 1
                     ├─── 1 [Payment] ─── N [BankAccount]
                     │
                     └─── N [Ticket]
```

### Detil Tabel & Atribut

#### 1. `users`
Tabel pengguna untuk Penonton, Admin, dan Super Admin.
- `id` (PK, BigInt)
- `name` (String)
- `email` (String, Unique)
- `password` (String, Nullable jika login Google)
- `google_id` (String, Nullable)
- `avatar` (String, Nullable)
- `email_verified_at` (Timestamp, Nullable)
- `remember_token` (String, Nullable)
- `timestamps`

#### 2. `venues`
Master data venue (reusable venue).
- `id` (PK, BigInt)
- `name` (String) — *contoh: "Gedung Kesenian Jakarta"*
- `address` (Text)
- `total_rows` (Integer)
- `total_columns` (Integer)
- `is_active` (Boolean, Default: true)
- `timestamps`

#### 3. `seat_categories`
Kategori/zona kursi pada venue beserta harga standar.
- `id` (PK, BigInt)
- `venue_id` (FK -> `venues.id`)
- `name` (String) — *contoh: "VIP Front", "Reguler Zone A", "Balkon"*
- `color_code` (String) — *hex code untuk tampilan visual denah, mis: "#FFD700"*
- `price` (Decimal 12,2) — *harga tetap per kategori kursi di venue ini*
- `timestamps`

#### 4. `seat_masters`
Master tata letak kursi individu dalam venue.
- `id` (PK, BigInt)
- `venue_id` (FK -> `venues.id`)
- `seat_category_id` (FK -> `seat_categories.id`)
- `seat_code` (String) — *contoh: "A-12", "B-05"*
- `row_num` (Integer)
- `col_num` (Integer)
- `is_active` (Boolean, Default: true — false jika tiang/aisle)
- `timestamps`

#### 5. `events`
Data event pentas seni yang dibuat oleh Admin.
- `id` (PK, BigInt)
- `venue_id` (FK -> `venues.id`)
- `created_by` (FK -> `users.id`)
- `title` (String)
- `slug` (String, Unique)
- `poster_path` (String)
- `description` (LongText)
- `event_category` (String, Default: "Pertunjukan")
- `payment_verification_timeout_hours` (Integer, Default: 24) — *batas jam verifikasi custom per event*
- `status` (Enum: `draft`, `published`, `completed`, `cancelled`)
- `timestamps`

#### 6. `event_sessions`
Jadwal / sesi pertunjukan untuk suatu event.
- `id` (PK, BigInt)
- `event_id` (FK -> `events.id`)
- `session_date` (Date)
- `start_time` (Time)
- `end_time` (Time)
- `timestamps`

#### 7. `seat_availabilities`
Status ketersediaan real-time setiap kursi pada sesi event tertentu.
- `id` (PK, BigInt)
- `event_session_id` (FK -> `event_sessions.id`)
- `seat_master_id` (FK -> `seat_masters.id`)
- `order_id` (FK -> `orders.id`, Nullable)
- `status` (Enum: `available`, `locked`, `sold`)
- `locked_until` (Timestamp, Nullable)
- `timestamps`
- *Unique Constraint*: `[event_session_id, seat_master_id]`

#### 8. `bank_accounts`
Rekening bank tujuan transfer yang dikelola Admin/Super Admin.
- `id` (PK, BigInt)
- `bank_name` (String) — *contoh: "BCA", "Mandiri", "BNI"*
- `account_number` (String)
- `account_holder` (String)
- `is_active` (Boolean, Default: true)
- `timestamps`

#### 9. `orders`
Pemesanan tiket oleh user.
- `id` (PK, BigInt)
- `order_code` (String, Unique) — *contoh: "NYA-20260815-XYZ8"*
- `user_id` (FK -> `users.id`)
- `event_session_id` (FK -> `event_sessions.id`)
- `bank_account_id` (FK -> `bank_accounts.id`, Nullable)
- `total_amount` (Decimal 12,2)
- `unique_code` (Integer, Default: 0) — *3 digit angka unik untuk memudahkan verifikasi*
- `final_amount` (Decimal 12,2) — *total_amount + unique_code*
- `status` (Enum: `pending_payment`, `waiting_verification`, `paid`, `cancelled`, `rejected`)
- `expired_at` (Timestamp) — *batas waktu bayar/upload bukti*
- `timestamps`

#### 10. `payments`
Data bukti pembayaran dan log verifikasi admin.
- `id` (PK, BigInt)
- `order_id` (FK -> `orders.id`)
- `proof_path` (String) — *path file gambar/PDF bukti transfer*
- `sender_bank` (String, Nullable)
- `sender_name` (String, Nullable)
- `transfer_amount` (Decimal 12,2, Nullable)
- `uploaded_at` (Timestamp)
- `verified_by` (FK -> `users.id`, Nullable) — *ID Admin yang me-review*
- `verified_at` (Timestamp, Nullable)
- `rejection_reason` (Text, Nullable)
- `timestamps`

#### 11. `tickets`
E-Tiket resmi yang terbit setelah pembayaran di-approve.
- `id` (PK, BigInt)
- `order_id` (FK -> `orders.id`)
- `seat_master_id` (FK -> `seat_masters.id`)
- `ticket_code` (String, Unique) — *kode e-tiket unik*
- `qr_code_path` (String)
- `is_scanned` (Boolean, Default: false)
- `scanned_at` (Timestamp, Nullable)
- `scanned_by` (FK -> `users.id`, Nullable)
- `timestamps`

---

## 3. Mekanisme State Machine & Timer Pelepasan Kursi

### Transisi Status `SeatAvailability`
```
[available] ──(User klik pilih kursi)──> [locked]
     │                                     │
     │                              ┌──────┴──────────────────────────┐
     │                     (Batas bayar/upload lewat)    (Admin APPROVE payment)
     │                              │                                 │
     │                              ▼                                 ▼
     └──────────────────────── [available]                       [sold]
                                    ▲
                                    │ (Admin REJECT payment)
                           [waiting_verification]
```

### Job Terjadwal (Scheduled Queue Worker)
1. **Seat Lock Expiration**: Ketika user memilih kursi di frontend, sistem mengunci kursi (`status = locked`, `locked_until = now() + 10 menit`). Job `ReleaseExpiredSeatsJob` dijalankan via scheduler Laravel untuk melepaskan kursi jika user tidak lanjut ke langkah checkout.
2. **Order Verification Expiration**: Jika order masuk status `waiting_verification` tetapi Admin belum meng-approve/reject hingga `payment_verification_timeout_hours` dari event terlampaui, job `CancelExpiredOrdersJob` otomatis membatalkan order dan melepaskan kursi kembali ke `available`.
