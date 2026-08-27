# 🚀 Panduan Lengkap Deploy Nanya Events ke Shared Hosting (cPanel / Hostinger / Niagahoster)

Dokumen ini berisi panduan *step-by-step* untuk mendistribusikan & mendepoloy aplikasi **Nanya Events (Laravel 11 + Filament 3 + Livewire 3)** ke Shared Hosting ber-cPanel atau Hostinger hPanel.

---

## 📋 Syarat & Spesifikasi Server Shared Hosting
Sebelum memulai, pastikan hosting Anda memiliki spesifikasi berikut:
- **PHP Version**: `PHP 8.2` atau `PHP 8.3` (Diatur via *Select PHP Version* di cPanel/Hostinger).
- **Ekstensi PHP Aktif**: `pdo_mysql`, `bcmath`, `ctype`, `fileinfo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `gd`, `zip`.
- **Database**: MySQL 5.7+ / MariaDB 10.3+.

---

## 📁 Langkah 1: Persiapan File Project di Lokal (Komputer Anda)

1. **Bersihkan Cache Lokal**:
   Jalankan command ini di terminal Laragon:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

2. **Ekspor Database SQL dari Laragon**:
   - Buka `http://localhost/phpmyadmin` atau HeidSQL.
   - Pilih database `nanya_events`.
   - Pilih menu **Export** ➔ Simpan file `.sql` (contoh: `nanya_events_dump.sql`) di komputer Anda.

3. **Kompres File Project menjadi ZIP**:
   Kompres seluruh folder project `nanya-events` menjadi `nanya-events.zip`.
   > ⚠️ **PENTING: JANGAN SERTAKAN FOLDER BERIKUT DALAM ZIP**:
   > - `node_modules/` (TIDAK PERLU DI-UPLOAD)
   > - `.git/`
   > - `.env` lokal Anda

---

## 🗄️ Langkah 2: Buat Database di Shared Hosting

1. Masuk ke **cPanel** / **Hostinger Panel**.
2. Buka menu **MySQL Databases** / **Database Manager**.
3. Buat Database Baru, contoh: `u12345_nanya_db`.
4. Buat User Database Baru, contoh: `u12345_nanya_user` & Password yang kuat.
5. Hubungkan User ke Database dengan mencentang **ALL PRIVILEGES**.
6. Buka **phpMyAdmin** di cPanel/Hostinger ➔ Pilih database `u12345_nanya_db` ➔ Pilih **Import** ➔ Upload file `nanya_events_dump.sql`.

---

## 📂 Langkah 3: Upload & Susun Folder di Shared Hosting

### Struktur Folder yang Aman & Direkomendasikan:
Di Shared Hosting, pisahkan core aplikasi dari direktori publik agar file `.env` dan sistem aman dari akses publik:

```text
/home/username/
├── nanya-app/             <-- Seluruh file Laravel (di luar public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── .env
│
└── public_html/           <-- Isi dari folder public/ Laravel
    ├── assets/
    ├── img/
    ├── storage/          <-- Symlink ke nanya-app/storage/app/public
    ├── .htaccess
    ├── favicon.ico
    └── index.php
```

### Langkah Upload:
1. Buka **File Manager** di cPanel/Hostinger.
2. Di luar `public_html` (di root `/home/username/`), buat folder baru bernama `nanya-app`.
3. Upload `nanya-events.zip` ke folder `nanya-app` lalu **Extract**.
4. Pindahkan seluruh isi folder `nanya-app/public/` ke dalam folder `public_html/`.

---

## ⚙️ Langkah 4: Edit File `public_html/index.php`

Buka file `public_html/index.php` via File Manager Edit, lalu ubah 2 baris path berikut agar mengarah ke folder `nanya-app`:

```php
// 1. Ubah Autoload Vendor Path
require __DIR__.'/../nanya-app/vendor/autoload.php';

// 2. Ubah Bootstrap App Path
$app = require_once __DIR__.'/../nanya-app/bootstrap/app.php';
```

---

## 🔒 Langkah 5: Buat & Konfigurasi File `.env` Produksi

Di dalam folder `/home/username/nanya-app/`, buat file `.env` baru dan isi dengan konfigurasi berikut:

```env
APP_NAME="Nanya Events"
APP_ENV=production
APP_KEY=base64:XG8... (Salin APP_KEY dari file .env lokal Anda)
APP_DEBUG=false
APP_URL=https://events.nanyang.sch.id

LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=namauser_nanyadb
DB_USERNAME=namauser_dbuser
DB_PASSWORD=PasswordDatabaseHostingAnda

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Google OAuth (Untuk Domain https://events.nanyang.sch.id)
GOOGLE_CLIENT_ID=984389283757-grjk7l0dkpf8dr2sprql6rhmkoqiapjl.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=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
GOOGLE_REDIRECT_URI=https://events.nanyang.sch.id/auth/google/callback
```

---

## 🔗 Langkah 6: Buat Symlink Storage (Agar Gambar/Poster/QR Code Tampil)

Agar file gambar poster, QR code, dan bukti transfer yang diunggah dapat diakses publik, buat link dari `nanya-app/storage/app/public` ke `public_html/storage`.

### Opsi A (Jika Memiliki Akses SSH/Terminal):
Jalankan command ini di terminal SSH:
```bash
ln -s /home/username/nanya-app/storage/app/public /home/username/public_html/storage
```

### Opsi B (Tanpa SSH / Menggunakan File Script PHP):
1. Di folder `public_html/`, buat file baru bernama `symlink.php`.
2. Isi file `symlink.php` dengan kode berikut:
   ```php
   <?php
   $target = '/home/username/nanya-app/storage/app/public';
   $shortcut = '/home/username/public_html/storage';
   
   if (symlink($target, $shortcut)) {
       echo '✅ Symlink Storage Berhasil Dibuat!';
   } else {
       echo '❌ Gagal Membuat Symlink!';
   }
   ```
3. Akses via browser: `https://domainanda.com/symlink.php`.
4. Setelah muncul tulisan `✅ Symlink Storage Berhasil Dibuat!`, **HAPUS FILE `symlink.php`** demi keamanan.

---

## 🚀 Langkah 7: Optimasi Kecepatan Produksi

Jika Anda memiliki akses **Terminal / SSH** di cPanel/Hostinger, jalankan command berikut di folder `nanya-app`:

```bash
cd /home/username/nanya-app
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:optimize
```

---

## ✅ SELESAI! Pengujian Akses Website

Aplikasi Anda kini telah aktif dan siap digunakan!
- **Halaman Utama Publik**: `https://domainanda.com`
- **Panel Admin Filament**: `https://domainanda.com/admin`
- **Scanner QR Code Tiket**: `https://domainanda.com/scan-ticket`
- **Live Monitor Kehadiran Kursi**: `https://domainanda.com/admin/seat-attendance`

---

## 🛠️ Solusi Tuntas "500 Internal Server Error" di cPanel

Jika setelah di-deploy muncul pesan **`500 Internal Server Error`**, berikut 4 penyebab utama dan cara mengatasinya secara urut:

### 1. Ubah Versi PHP ke PHP 8.2 / 8.3 (Paling Sering Terjadi)
Aplikasi Laravel 11 + Filament 3 **Wajib** berjalan di PHP 8.2 atau 8.3.
- Buka cPanel ➔ **Select PHP Version** (atau **MultiPHP Manager**).
- Pilih domain/subdomain `events.nanyang.sch.id`.
- Ubah versi PHP dari default *(misal PHP 7.4/8.0)* menjadi **`PHP 8.2`** atau **`PHP 8.3`**.
- Centang ekstensi wajib: `pdo_mysql`, `bcmath`, `fileinfo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `gd`, `zip`.

### 2. Perbaiki Permission File & Folder (Aturan Keamanan suPHP cPanel)
Jika di error log cPanel muncul `SoftException: File is writeable by group`, ini terjadi karena suPHP menolak eksekusi file `.php` yang memiliki permission group-write (seperti 664/775/777).

**Aturan Permission Wajib suPHP cPanel**:
- **Seluruh File `.php` (termasuk `index.php`)**: Wajib **`644`** *(JANGAN 777/775/664)*.
- **Seluruh Folder (termasuk `storage/` & `bootstrap/cache/`)**: Wajib **`755`**.

**Langkah Perbaikan `index.php`**:
1. Buka File Manager cPanel di folder `public/` (atau folder subdomain Anda).
2. Klik kanan file **`index.php`** ➔ **Change Permissions**.
3. Centang/ubah nilainya menjadi **`644`**.

### 3. Perbaiki Error `Read-only file system` pada `storage/logs/laravel.log`
Jika di log muncul `UnexpectedValueException: The stream or file "/home/nanya/nanya-events/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Read-only file system`:

1. Buka File Manager CWP ➔ Masuk ke folder `/home/nanya/nanya-events/storage/`.
2. Klik kanan folder **`logs`** ➔ **Change Permissions** ➔ Ubah menjadi **`775`** atau **`777`**.
3. Jika file `storage/logs/laravel.log` sudah ada tetapi tidak bisa ditulis, **HAPUS file `laravel.log` tersebut** agar Laravel dapat membuat file log baru dengan izin tulis yang benar.

### 3. Periksa Path di `index.php` Subdomain
Pastikan path pada `public_html/events/index.php` mengarah dengan benar ke folder `nanya-app`:
```php
require __DIR__.'/../../nanya-app/vendor/autoload.php';
$app = require_once __DIR__.'/../../nanya-app/bootstrap/app.php';
```

### 4. Perbarui File `.htaccess`
Jika server Apache melarang instruksi `Options`, ganti isi file `.htaccess` di dalam `public_html/events/.htaccess` menjadi versi aman berikut:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---
*Dokumen ini dibuat otomatis oleh AI Agent Antigravity untuk project Nanya Events.*
