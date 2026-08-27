# 🗺️ Phased Roadmap & Detailed Task Breakdown

Dokumen ini memuat tahapan pengembangan (roadmaps) dan daftar tugas (checklists) untuk pembuatan **Nanya Events Platform**.

---

## 📊 Overview Status Roadmap

```
[Phase 1: Setup] ──> [Phase 2: Venue Engine] ──> [Phase 3: Admin Event]
                                                        │
[Phase 7: Testing] <── [Phase 6: E-Ticket] <── [Phase 5: Payment Flow] <── [Phase 4: User Frontend]
```

- **Kemajuan Total**: 100% (Seluruh Roadmap Selesai & Terverifikasi!)
- **Fase Saat Ini**: Project Complete — Seluruh Fitur Telah Siap Digunakan! 🎉

---

## 🎯 Detail Task Per Fase

### 🟢 Fase 1: Setup Environment, Project Foundation & Authentication (SELESAI)
- [x] `[1.1]` Inisialisasi Project Laravel 12 & Konfigurasi `.env` (Database, App Name, Mail).
- [x] `[1.2]` Instalasi Filament 4.x & Filament Shield (`bezhansalleh/filament-shield`).
- [x] `[1.3]` Konfigurasi Role & Permission Baseline (`Super Admin`, `Admin`, `User`).
- [x] `[1.4]` Setup Laravel Socialite untuk Google OAuth 2.0.
- [x] `[1.5]` Implementasi Form Login / Register manual (Email & Password) dengan email verification.
- [x] `[1.6]` Fitur Lupa & Reset Password Penonton via Email Tautan (`ForgotPasswordController`, Views Glassmorphism, & Rute Guest).

### 🟢 Fase 2: Master Venue & Engine Denah Kursi (Super Admin) (SELESAI)
- [x] `[2.1]` Membuat Migration & Model `Venue`, `SeatCategory`, `SeatMaster`.
- [x] `[2.2]` Membuat Filament Resource `VenueResource` (Super Admin access only).
- [x] `[2.3]` Membuat Filament Resource `SeatCategoryResource` (Manajemen kategori kursi & harga tetap).
- [x] `[2.4]` Implementasi Generator Grid Kursi (`SeatMaster`) otomatis berdasarkan baris & kolom venue.
- [x] `[2.5]` Tool Visual Editor Denah Kursi sederhana di Filament (mengatur status aktif/nonaktif & kategori kursi).

### 🟢 Fase 3: Manajemen Event & Sesi (Admin & Super Admin Panel) (SELESAI)
- [x] `[3.1]` Membuat Migration & Model `Event`, `EventSession`, `SeatAvailability`, `BankAccount`.
- [x] `[3.2]` Membuat Filament Resource `EventResource` (Upload poster 3:4, rich text description, timeout verifikasi, status draft/publish).
- [x] `[3.3]` Relation Manager `EventSession` di dalam `EventResource` (Tanggal, jam mulai, jam selesai).
- [x] `[3.4]` Observer / Event Listener: Otomatis generate record `SeatAvailability` (`status = available`) saat `EventSession` baru dibuat.
- [x] `[3.5]` Membuat Filament Resource `BankAccountResource` (Manajemen rekening tujuan transfer).

### 🟢 Fase 4: Frontend Publik Penonton & Interactive Seat Map (SELESAI)
- [x] `[4.1]` Layout Frontend Utama (Header, Footer, Dark/Light Mode, Navigation Bar) menggunakan Livewire/Blade & Tailwind CSS.
- [x] `[4.2]` Halaman Katalog Event (`/events` & `/`) — Grid Poster Event, Search, Filter Kategori.
- [x] `[4.3]` Halaman Detail Event (`/events/{slug}`) — Poster, Deskripsi, Pilihan Sesi, Modal Login Enforcement.
- [x] `[4.4]` Halaman Seat Selection Interactive Engine (`/events/{slug}/sessions/{session_id}/seats`) — Tampilan SVG/CSS Denah Venue, Kategori Harga (VIP/Reguler), Indikator Kursi Terisi/Terpilih.
- [x] `[4.5]` Backend Real-time Seat Lock Logic — Kunci kursi saat diklik (`status = locked`), set timer 10 menit.

### 🟢 Fase 5: Alur Checkout, Pembayaran Manual Bank Transfer & Queues (SELESAI)
- [x] `[5.1]` Halaman Checkout Ringkasan Order (`/checkout/{order_code}`) — Rincian Kursi, Kode Unik Transfer, Pilihan Rekening Bank Tujuan.
- [x] `[5.2]` Form Upload Bukti Transfer (Gambar/PDF, Informasi Bank Pengirim).
- [x] `[5.3]` Transisi Order Status: `pending_payment` -> `waiting_verification`.
- [x] `[5.4]` Setup Scheduled Job `ReleaseExpiredSeatsJob` (Membatalkan locking kursi jika user tidak checkout dalam 10 menit).
- [x] `[5.5]` Setup Scheduled Job `CancelExpiredOrdersJob` (Membatalkan order jika bukti transfer tidak diverifikasi dalam kurun waktu custom event).

### 🟢 Fase 6: Panel Verifikasi Pembayaran Admin & Penerbitan E-Tiket (SELESAI)
- [x] `[6.1]` Membuat Filament Resource / Page `OrderResource` (Antrian pembayaran `waiting_verification` dengan notifikasi badge).
- [x] `[6.2]` Modal Preview Bukti Transfer & Tombol Action `Approve` / `Reject` dengan input alasan penolakan.
- [x] `[6.3]` Logic Action `Approve`: Ubah status Order ke `paid`, ubah status `SeatAvailability` ke `sold`, generate E-Tiket dengan QR Code unik (`tickets` table).
- [x] `[6.4]` Integration Notification: Filament toast notification saat order disetujui atau ditolak.
- [x] `[6.5]` Halaman Tiket Saya (`/my-tickets`) untuk Penonton — Tampilan Digital E-Pass & QR Code SVG Render.

### 🟢 Fase 7: Validasi QR Code Petugas & Audit Log (SELESAI)
- [x] `[7.1]` Halaman Scanner QR Tiket khusus Petugas Pintu Masuk (`/scan-ticket`).
- [x] `[7.2]` Logic Validasi Scan QR Code (Check idempotency — pastikan 1 tiket hanya bisa di-scan 1 kali).
- [x] `[7.3]` Implementasi Log Audit Admin & Pencatatan Petugas Scanner (`scanned_by` & `scanned_at`).
- [x] `[7.4]` End-to-End Automated Testing & User Acceptance Testing (UAT).
