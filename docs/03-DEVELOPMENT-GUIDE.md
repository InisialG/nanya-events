# 🛠️ Development & Session Management Guide

Dokumen ini berisi panduan teknis pengembangan project **Nanya Events**, standar penulisan kode, serta **prosedur wajib pengelolaan session** agar setiap pergantian session (baik antar developer maupun AI Agent) berjalan mulus tanpa kehilangan konteks.

---

## 1. Prasyarat & Lingkungan Pengembangan

- **OS**: Windows (Laragon environment disarankan) / Linux / macOS
- **PHP**: ^8.3 (dengan ekstensi `pdo`, `mbstring`, `openssl`, `gd`/`imagick`, `curl`)
- **Composer**: ^2.7
- **Node.js**: ^20.x & npm
- **Database**: MySQL 8.0+ / MariaDB 10.11+
- **Web Server**: Nginx / Apache via Laragon (`http://nanya-events.test`)

---

## 2. Perintah Penting (Command Cheatsheet)

```bash
# Setup awal project
composer install
npm install
cp .env.example .env
php artisan key:generate

# Database & Seeding
php artisan migrate:fresh --seed

# Filament Shield (Role & Permission Generator)
php artisan shield:generate --all

# Asset Compilation & Dev Server
npm run dev
php artisan serve

# Queue Worker (PENTING untuk Seat Timer & Emails)
php artisan queue:work

# Scheduler (Jalankan lokal untuk pengujian timer release)
php artisan schedule:work
```

---

## 3. Aturan Wajib Pengelolaan Session (Session Memory Rules)

> ⚠️ **ATURAN UTAMA BAGI AI AGENT / DEVELOPER**:
> Dokumentasi project ini adalah **single source of truth** bagi memory lintas session. Setiap kali sebuah tugas atau sub-tugas diselesaikan, Anda **WAJIB** memperbarui file `[docs/SESSION-LOG.md](file:///e:/laragon/www/nanya-events/docs/SESSION-LOG.md)`.

### Langkah-langkah Wajib saat Mengakhiri / Menyelesaikan Tugas:

1. **Buka [SESSION-LOG.md](file:///e:/laragon/www/nanya-events/docs/SESSION-LOG.md)**.
2. **Perbarui Status Terkini**: Update persentase kemajuan total dan fase yang sedang berjalan.
3. **Tambahkan Catatan Riwayat Session**:
   - Tanggal & Waktu.
   - Ringkasan tugas yang diselesaikan.
   - File yang dibuat / diubah (`[MODIFIED]`, `[NEW]`, `[DELETED]`).
   - Keputusan arsitektur atau teknis baru yang diambil (jika ada).
4. **Perbarui [02-ROADMAP-TASKS.md](file:///e:/laragon/www/nanya-events/docs/02-ROADMAP-TASKS.md)**: Tandai `[x]` pada task yang selesai diselesaikan.
5. **Update Instuksi Session Berikutnya**: Berikan arahan yang spesifik dan jelas mengenai apa yang harus dikerjakan oleh session selanjutnya.

---

## 4. Standar Kode & Konvensi

- **Naming Conventions**:
  - Class/Model: `PascalCase` (`EventSession`, `SeatAvailability`).
  - Tables & Columns: `snake_case` (`event_sessions`, `locked_until`).
  - Filament Resources: `EventResource`, `VenueResource`.
- **Database & Query**:
  - Selalu gunakan database transaction (`DB::transaction`) pada operasi kritis seperti pemesanan kursi, kunci kursi, dan verifikasi pembayaran untuk menghindari kondisi race condition.
- **Seat Map Engine**:
  - Komponen visual denah kursi publik menggunakan Livewire dengan interaksi dynamic SVG/CSS Grid agar ringan dan responsif di perangkat mobile.
