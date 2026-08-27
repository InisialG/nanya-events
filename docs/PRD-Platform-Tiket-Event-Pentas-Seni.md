# PRD: Platform Tiket & Booking Kursi untuk Event Pentas Seni

**Versi:** 0.2 (Draft)
**Status:** Untuk didiskusikan
**Pemilik Dokumen:** [diisi]
**Tanggal:** 15 Agustus 2026

---

## 1. Latar Belakang & Tujuan

Platform ini digunakan untuk menjual tiket **berbagai event pentas seni** (pertunjukan tari, teater, konser kampus/komunitas, dsb.) yang diselenggarakan di **lokasi/venue yang sama**, namun tiap event memiliki poster, deskripsi, tanggal, dan detail sendiri-sendiri.

Karena venue-nya tetap, **denah kursi cukup didesain sekali** dan dipakai ulang oleh admin untuk event apa pun — admin tidak perlu menggambar ulang denah tiap kali membuat event baru.

**Tujuan utama:**
- Admin dapat membuat event baru dengan cepat (poster, deskripsi, jadwal, harga) tanpa perlu mengulang setup denah kursi.
- Penonton dapat melihat daftar event, memilih event, memilih kursi dari denah venue, dan membayar tiketnya secara online.
- Status ketersediaan kursi terpisah per event/sesi, walau venue sama.

**Metrik keberhasilan (KPI):**
| Metrik | Target |
|---|---|
| Waktu admin membuat event baru (poster → publish) | ≤ 5 menit |
| Conversion rate (lihat event → checkout selesai) | ≥ 30% |
| Waktu rata-rata penyelesaian transaksi pengguna | ≤ 3 menit |
| Tingkat kegagalan pembayaran | ≤ 3% |

---

## 2. Aktor / Pengguna

- **Admin/Panitia**: membuat & mengelola event, memantau penjualan, generate laporan.
- **Penonton**: browse event, pilih kursi, beli tiket, terima e-tiket.
- **Petugas pintu masuk (opsional)**: scan QR e-tiket saat hari-H.

---

## 3. Ruang Lingkup

**In-scope (v1):**
- Admin: CRUD Event (poster, deskripsi, kategori acara, tanggal/jam, harga per kategori kursi).
- Admin: Setup Venue & Denah Kursi (dibuat sekali, reusable untuk semua event).
- Penonton: Halaman daftar event (landing/katalog) dengan poster & deskripsi.
- Penonton: Detail event → pilih sesi/jadwal (jika ada beberapa) → pilih kursi dari denah → checkout → bayar.
- Penerbitan e-tiket (QR code) & notifikasi.
- Dashboard admin: rekap penjualan & okupansi kursi per event.

**Out-of-scope (v1):**
- Multi-venue dengan denah berbeda-beda (asumsi: 1 venue utama; multi-venue bisa fase berikutnya).
- Refund/reschedule otomatis kompleks (bisa manual dulu oleh admin).
- Penjualan merchandise atau F&B.

---

## 4. Konsep Kunci: Venue Reusable vs Event Spesifik

Ini beda paling penting dari versi bioskop sebelumnya:

| Sebelumnya (bioskop) | Sekarang (pentas seni) |
|---|---|
| Studio & denah kursi banyak, beda-beda per bioskop | **Venue & denah kursi satu, dipakai berulang** |
| Jadwal tayang (showtime) melekat ke studio | **Event dibuat admin secara manual**, lengkap dengan konten (poster, deskripsi) |
| Kursi terisi dicatat per showtime | Kursi terisi dicatat **per Event + Sesi**, bukan per venue |

Artinya struktur datanya:

```
Venue (didesain 1x)
  └── Denah Kursi (baris, kolom, tipe kursi, kategori harga per zona)

Event (dibuat berkali-kali oleh admin)
  ├── Poster, deskripsi, kategori acara, syarat & ketentuan
  └── Sesi/Jadwal (bisa lebih dari satu tanggal/jam)
        └── referensi ke Venue yang sama (harga ikut harga kategori
            kursi yang sudah ditetapkan di Venue — TIDAK di-set ulang
            per event)

SeatAvailability (per Event + Sesi + Seat)
  → status: tersedia / terkunci / terjual
```

Dengan begini, saat admin bikin event baru, sistem otomatis "meng-clone" status kursi kosong dari denah venue untuk sesi tersebut — admin tinggal isi konten event (poster, deskripsi, jadwal), **tidak perlu menata ulang kursi maupun menentukan ulang harga**, karena harga sudah melekat pada kategori/lokasi kursi di venue dan otomatis berlaku sama untuk semua event/sesi yang memakai venue tersebut.

> **Catatan:** jika suatu saat dibutuhkan harga berbeda per event (mis. event A lebih mahal dari event B walau kursi sama), ini jadi kemungkinan pengembangan fase berikutnya — v1 harga tetap mengikuti kategori kursi di venue.

---

## 5. Alur Admin: Membuat Event Baru

```
1. Login admin
     ↓
2. Klik "Buat Event Baru"
     ↓
3. Isi informasi event:
   - Upload poster/flyer
   - Judul event
   - Kategori (tari, teater, musik, dll.)
   - Deskripsi kegiatan
   - Lokasi (pilih venue — default 1 opsi jika hanya 1 venue)
     ↓
4. Atur Sesi/Jadwal (bisa lebih dari 1 tanggal/jam tayang)
     ↓
5. Preview event (harga per kursi otomatis mengikuti kategori kursi
   yang sudah ditetapkan di denah venue — admin tidak input harga lagi)
     ↓
6. Publish → event tampil di katalog publik
```

### Kebutuhan Fungsional — Admin
- FR-A1: Admin dapat mengunggah poster (format gambar, rasio disarankan potrait 3:4 atau sesuai standar).
- FR-A2: Admin mengisi judul, deskripsi (rich text/markdown), kategori acara, dan tag.
- FR-A3: Admin dapat menambahkan satu atau lebih sesi (tanggal & jam) untuk event yang sama.
- FR-A4: Admin memilih venue dari daftar venue yang sudah terdaftar (v1 cukup 1 default venue).
- FR-A5: Admin **tidak perlu** mengatur harga saat membuat event — harga otomatis mengikuti harga per kategori kursi yang sudah ditetapkan di denah venue (lihat FR-V1). Halaman preview event menampilkan rentang harga (mis. "Rp50.000 – Rp150.000") secara otomatis berdasarkan kategori kursi yang tersedia.
- FR-A5b: Admin memilih **kategori acara** (mis. Pertunjukan, dan kategori lain untuk pengembangan mendatang). **v1 hanya mendukung kategori "Pertunjukan"** dengan alur booking kursi seperti yang dijabarkan di dokumen ini; kategori acara lain akan memiliki tampilan/alur user berbeda dan menjadi cakupan pengembangan fase berikutnya (lihat Bagian 16).
- FR-A6: Admin dapat menyimpan sebagai draft atau langsung publish.
- FR-A6b: Admin mengisi "batas waktu verifikasi pembayaran" (dalam jam) khusus untuk event tersebut — menentukan berapa lama kursi terkunci menunggu bukti transfer diverifikasi sebelum otomatis dilepas.
- FR-A7: Admin dapat mengedit/menonaktifkan event yang sudah publish (mis. jika kursi sudah terjual, field venue/harga tidak bisa diubah sembarangan).
- FR-A8: Dashboard admin menampilkan status penjualan per event & per sesi (jumlah kursi terjual/tersedia, total pendapatan).

### Kebutuhan Fungsional — Setup Venue (dibuat terpisah, jarang berubah)
- FR-V1: Super Admin dapat membuat/mengedit denah venue: baris, kolom, kategori kursi (VIP/Reguler/dll.), gang/aisle, kursi nonaktif (kolom, tiang, dsb.) — sesuai kapabilitas custom seat layout yang sudah dibahas sebelumnya.
- FR-V1b: Setiap **kategori kursi** (VIP/Reguler/Balkon/dll.) memiliki **harga tetap** yang ditetapkan di level venue ini — bukan di level event. Harga ini otomatis berlaku untuk semua event & sesi yang menggunakan venue tersebut.
- FR-V2: Denah venue (beserta harga per kategori kursi) ini menjadi acuan untuk semua event yang memilih venue tersebut.
- FR-V3: Perubahan pada denah venue (jarang terjadi) sebaiknya tidak memengaruhi event yang sudah berjalan/terjual — perlu strategi versioning denah agar histori transaksi tetap konsisten.

---

## 6. Alur Penonton: Browse Event → Booking Kursi → Bayar

```
1. Buka halaman katalog event (list poster + judul + tanggal singkat)
     ↓
2. Klik salah satu event → halaman detail
   (poster besar, deskripsi lengkap, pilihan sesi jika ada beberapa)
     ↓
3. Pilih sesi/jadwal
     ↓
4. Pilih kursi dari denah (denah sama seperti venue, tapi status kursi
   khusus untuk sesi ini)
     ↓  (seat lock sementara + timer)
5. Ringkasan pesanan (event, sesi, kursi, kategori harga, total)
     ↓
6. Isi data pemesan (nama, email/no. HP) — login atau guest
     ↓
7. Pilih metode pembayaran → proses bayar
     ↓
8a. Berhasil → e-tiket (QR code) diterbitkan + notifikasi
8b. Gagal/timeout → kursi dilepas, arahkan retry
     ↓
9. E-tiket tersimpan di "Tiket Saya"
```

### Kebutuhan Fungsional — Penonton
- FR-P1: Halaman katalog menampilkan daftar event aktif (poster, judul, tanggal terdekat, kategori, indikator "hampir habis"/"sold out").
- FR-P2: Halaman detail event menampilkan poster, deskripsi lengkap, daftar sesi (jika lebih dari satu), dan rentang harga.
- FR-P3: Pengguna memilih sesi terlebih dahulu sebelum masuk ke denah kursi (karena ketersediaan kursi berbeda per sesi).
- FR-P4: Denah kursi menampilkan status sesuai kategori harga (warna berbeda per kategori: VIP, Reguler, dst.) dan status keterisian (kosong/dipilih/terisi/terkunci).
- FR-P5: Seat-lock sementara saat kursi dipilih, dengan timer countdown, sama seperti mekanisme sebelumnya.
- FR-P6: Ringkasan pesanan otomatis menghitung total berdasarkan kategori kursi terpilih.
- FR-P7: Pembayaran melalui payment gateway (kartu, e-wallet, QRIS, VA).
- FR-P8: E-tiket berisi QR unik per kursi, dapat divalidasi satu kali oleh petugas saat hari-H.

---

## 7. Struktur Data (Model Utama, Direvisi)

| Entitas | Atribut Kunci |
|---|---|
| **Venue** | id, nama, alamat, layout_denah |
| **SeatCategory** | id, venue_id, nama_kategori (VIP/Reguler/dll.), **harga** |
| **SeatMaster** | id, venue_id, seat_category_id, kode_kursi, posisi (baris, offset), aktif/nonaktif |
| **Event** | id, judul, poster_url, deskripsi, kategori_acara (v1: "Pertunjukan"), venue_id, batas_jam_verifikasi_pembayaran, status (draft/publish/selesai) |
| **EventSession** | id, event_id, tanggal, jam_mulai, jam_selesai |
| **SeatAvailability** | event_session_id, seat_master_id, status (tersedia/terkunci/terjual) |
| **Order** | id, event_session_id, user_id/guest_info, daftar_seat, total, status |
| **Payment** | id, order_id, metode, gateway_ref_id, status |
| **Ticket** | id, order_id, seat_id, kode_booking, qr_code, status_scan |

Poin penting:
- `SeatAvailability` dibuat baru untuk **setiap EventSession**, bukan disimpan permanen di `SeatMaster`. Ini yang memungkinkan venue sama dipakai ulang tanpa bentrok antar event.
- **Harga melekat di `SeatCategory` (level venue)**, bukan di `Event`/`EventSession`. Jadi seluruh event yang memakai venue yang sama otomatis punya struktur harga yang sama, dan admin tidak perlu input harga berulang setiap membuat event baru.

---

## 8. Kebutuhan Non-Fungsional
(mengikuti dokumen sebelumnya — performa denah real-time, no double-booking, keamanan pembayaran via gateway bersertifikasi, aksesibilitas, skalabilitas saat lonjakan pembelian tiket event populer.)

---

## 9. Edge Case Tambahan (khusus multi-event, 1 venue)

| Skenario | Penanganan |
|---|---|
| Admin ubah denah venue setelah ada event yang sudah terjual | Perlu versioning — event lama tetap pakai snapshot denah lama |
| Dua event dijadwalkan di venue sama pada waktu tumpang tindih | Validasi saat admin input sesi baru: cek bentrok jadwal venue |
| Event dengan lebih dari 1 sesi, harga beda per sesi (mis. weekday vs weekend) | `EventSeatPrice` sebaiknya di-scope ke session, bukan hanya ke event, jika harga bisa berbeda per sesi |
| Event dibatalkan setelah tiket terjual | Refund massal ke semua pemegang tiket sesi tersebut + notifikasi |

---

## 10. Open Questions

1. Apakah harga kursi bisa berbeda antar sesi dalam event yang sama (mis. harga weekday vs weekend berbeda)?
2. Apakah nanti akan ada lebih dari satu venue (multi-lokasi), atau v1 cukup 1 venue saja?
3. Apakah perlu approval/moderasi sebelum event yang dibuat admin lain tayang publik (jika ada multi-admin/panitia)?
4. Apakah tiket bisa dipindahtangankan (transfer ke nama lain) sebelum hari-H?
5. Apakah butuh kuota kursi khusus (mis. reserved untuk sponsor/tamu VIP) yang tidak dijual ke publik?

---

*Dokumen ini revisi dari draft sebelumnya, menyesuaikan konsep: platform event pentas seni dengan venue/denah kursi reusable dan event yang dibuat dinamis oleh admin.*

---

## 11. Revisi: Autentikasi & Manajemen Peran (Role)

> **Perubahan dari draft sebelumnya:** guest checkout dihilangkan — semua pengguna **wajib login** sebelum bisa booking kursi.

### 11.1 Metode Login/Registrasi
- **Login/Daftar dengan Google (OAuth 2.0)** — one-click, sistem otomatis ambil nama & email dari akun Google.
- **Login/Daftar manual** — form email + password, dengan verifikasi email (kirim link/kode OTP) sebelum akun aktif.
- Jika email yang dipakai daftar manual sama dengan email Google, sebaiknya sistem menyatukan akun (account linking) berdasarkan email agar tidak duplikat.

### 11.2 Kebutuhan Fungsional — Autentikasi
- FR-AUTH1: Sistem menyediakan tombol "Masuk dengan Google" di halaman login/registrasi.
- FR-AUTH2: Sistem menyediakan form registrasi manual (nama, email, password, konfirmasi password) dengan verifikasi email.
- FR-AUTH3: Sistem menyediakan form login manual (email + password) dengan opsi "lupa password".
- FR-AUTH4: Sesi login menggunakan token (mis. JWT) dengan masa berlaku tertentu, refresh token untuk sesi panjang.
- FR-AUTH5: Booking kursi dan checkout **hanya bisa diakses oleh pengguna yang sudah login** — tombol "Pilih Kursi" mengarahkan ke login dulu jika belum masuk.
- FR-AUTH6: Satu email hanya terdaftar sebagai satu akun (baik dari Google maupun manual).

### 11.3 Peran & Hak Akses (Role)

| Fitur | User | Admin | Super Admin |
|---|:---:|:---:|:---:|
| Login (Google/manual) | ✅ | ✅ | ✅ |
| Browse & booking event | ✅ | — | — |
| Upload bukti transfer | ✅ | — | — |
| Lihat tiket & riwayat transaksi sendiri | ✅ | — | — |
| Dashboard: daftar pembayaran masuk | — | ✅ | ✅ |
| **Approve/reject bukti pembayaran** | — | ✅ | ✅ |
| Buat/edit event (poster, deskripsi, sesi, harga) | — | ✅ | ✅ |
| Lihat laporan penjualan per event | — | ✅ | ✅ |
| Kelola/edit **denah venue** | — | — | ✅ |
| Kelola akun Admin (tambah/hapus/nonaktifkan) | — | — | ✅ |
| Kelola semua event dari semua admin | — | — | ✅ |
| Lihat laporan keseluruhan platform (semua event, semua admin) | — | — | ✅ |
| **Kelola rekening bank tujuan transfer** | — | ✅ | ✅ |

> **Keputusan:** semua Admin setara (bukan role granular per-event) — Admin mana pun dapat membuat/mengedit event apa pun dan meng-approve/reject pembayaran untuk event apa pun, tidak dibatasi hanya event miliknya sendiri.

---

## 12. Revisi: Alur Pembayaran (Transfer Bank Manual + Approval Admin)

> **Perubahan dari draft sebelumnya:** tidak lagi otomatis via payment gateway real-time. Pembayaran dilakukan manual oleh user (transfer ke rekening yang ditentukan), lalu **diverifikasi/di-approve oleh Admin**.

```
1. User pilih kursi → checkout → ringkasan pesanan
     ↓
2. Sistem menampilkan info rekening tujuan transfer (bank, no. rekening,
   nominal yang harus dibayar, kode unik/berita transfer jika perlu)
     ↓
3. User melakukan transfer manual via bank/mobile banking
     ↓
4. User upload bukti transfer (foto/screenshot) + isi nominal & waktu transfer
     ↓
5. Order berstatus "Menunggu Verifikasi" — kursi TETAP terkunci (locked)
   selama menunggu, dengan batas waktu yang **diatur per event oleh
   admin saat membuat/mengedit event** (mis. event A: 12 jam, event B:
   24 jam), agar tidak menggantung kursi selamanya
     ↓
6. Admin melihat daftar pembayaran masuk di dashboard
     ↓
7a. Admin APPROVE → status jadi "Lunas" → e-tiket (QR code) diterbitkan
    otomatis + notifikasi ke user
7b. Admin REJECT (mis. bukti tidak valid/nominal salah) → user mendapat
    notifikasi alasan penolakan, kursi dilepas kembali ke pool tersedia
```

### Kebutuhan Fungsional — Pembayaran
- FR-PAY1: Sistem menampilkan detail rekening tujuan (bisa lebih dari satu bank). Daftar rekening ini **dapat dikelola oleh Admin maupun Super Admin** (tambah/edit/nonaktifkan rekening bank tujuan transfer).
- FR-PAY2: Sistem menghasilkan kode unik/nominal unik per transaksi (mis. Rp 150.000 + 3 = Rp 150.003) agar admin mudah mencocokkan mutasi.
- FR-PAY3: User dapat mengunggah bukti transfer (gambar, maks. ukuran tertentu, format jpg/png/pdf).
- FR-PAY4: Order dengan status "Menunggu Verifikasi" tetap mengunci kursi terkait, dengan batas waktu maksimum yang **dikonfigurasi per event** (field "batas waktu verifikasi pembayaran" saat admin membuat/mengedit event, satuan jam) sebelum otomatis dibatalkan jika tidak diverifikasi. Jika admin tidak mengisi, gunakan nilai default sistem (mis. 24 jam).
- FR-PAY5: Admin dapat melihat detail bukti transfer, nominal, dan waktu upload sebelum approve/reject.
- FR-PAY6: Sistem mencatat log siapa (admin mana) yang melakukan approve/reject dan kapan, untuk audit trail.
- FR-PAY7: Setelah approve, sistem otomatis generate e-tiket dan mengirim notifikasi (email/in-app) ke user.
- FR-PAY8: Setelah reject, sistem mengirim notifikasi alasan penolakan dan memberi opsi user upload ulang bukti transfer (jika masih dalam batas waktu) atau booking ulang.

---

## 13. Dashboard Admin & Super Admin

### 13.1 Dashboard Admin
- **Antrian pembayaran**: daftar order berstatus "Menunggu Verifikasi", dengan info user, event, kursi, nominal, bukti transfer, tombol Approve/Reject.
- **Daftar event**: event yang dikelola admin tersebut (buat/edit/publish/nonaktifkan).
- **Laporan penjualan**: jumlah tiket terjual, pendapatan, okupansi kursi — per event/sesi.
- **Riwayat approval**: histori pembayaran yang sudah diproses admin tersebut.

### 13.2 Dashboard Super Admin
Semua fitur Admin, ditambah:
- **Kelola Venue & Denah Kursi**: buat/edit layout kursi master.
- **Kelola Akun Admin**: tambah/hapus/nonaktifkan admin, atur hak akses.
- **Laporan Global**: rekap seluruh event dari seluruh admin, total pendapatan platform, grafik tren penjualan.
- **Pengaturan Rekening & Metode Pembayaran**: atur daftar rekening bank tujuan transfer.
- **Log Audit**: semua aktivitas approve/reject/edit event/edit venue di seluruh sistem.

### Kebutuhan Fungsional — Dashboard
- FR-DASH1: Dashboard Admin menampilkan badge/notifikasi jumlah pembayaran yang menunggu approval.
- FR-DASH2: Dashboard Super Admin menampilkan ringkasan lintas-event (total pendapatan, total tiket terjual, event paling laris).
- FR-DASH3: Role-based access control (RBAC) memastikan Admin biasa tidak bisa mengakses menu kelola venue/kelola akun admin.

---

## 14. Update Edge Case — Pembayaran Manual

| Skenario | Penanganan |
|---|---|
| User upload bukti transfer tapi tidak transfer sungguhan (bukti palsu/nominal tidak cocok) | Admin reject, kursi dilepas; opsional: sistem tandai user untuk pengawasan lebih ketat |
| Batas waktu verifikasi 24 jam terlewati, admin belum sempat cek | Order otomatis dibatalkan, kursi dilepas, user dapat notifikasi & bisa booking ulang |
| Dua admin approve order yang sama bersamaan | Perlu locking di level approval (idempotent action) agar tidak double-process |
| User transfer lebih/kurang dari nominal | Admin reject dengan catatan alasan, arahkan user upload ulang bukti yang benar atau hubungi CS |
| Akun Google dinonaktifkan/dihapus user | Sesi login invalid, tapi riwayat tiket & transaksi tetap tersimpan di sistem berbasis email |

---

## 15. Update Open Questions

**Sudah diputuskan:**
- ✅ Semua Admin setara — bisa kelola/approve event apa pun, tidak dibatasi per-admin.
- ✅ Batas waktu "Menunggu Verifikasi" bersifat custom per event (diatur admin saat membuat event), bukan angka tetap global.

**Masih perlu diputuskan:**
1. Apakah kode unik nominal transfer (mis. +Rp 3) wajib, atau cukup berita transfer manual?
2. Apakah perlu multi-approval (2 admin) untuk nominal besar, atau cukup 1 admin approve sudah final?
3. Apakah user bisa reset password sendiri (lupa password via email), atau lewat bantuan admin?
4. Apakah super admin perlu approve pendaftaran akun Admin baru, atau begitu ditambahkan langsung aktif?

---

## 16. Kategori Acara Sebagai Penentu Flow/Tampilan

Setiap Event memiliki field **kategori acara**. Kategori ini menentukan **alur dan tampilan booking** yang dilihat penonton — karena tidak semua jenis acara cocok dengan model "pilih kursi dari denah" (mis. acara berdiri/festival mungkin cukup jual tiket tanpa nomor kursi).

**v1 (cakupan dokumen ini): kategori "Pertunjukan"**
- Menggunakan seluruh alur yang sudah dirancang di dokumen ini: pilih sesi → pilih kursi dari denah venue → checkout → transfer bank → approval admin → e-tiket ber-QR per kursi.

**Kategori lain (di luar cakupan v1, disiapkan sebagai fondasi arsitektur untuk pengembangan berikutnya):**
- Sistem sebaiknya dirancang agar field `kategori_acara` pada Event bisa dipakai sebagai penentu komponen/flow booking yang dirender (mis. via strategi/factory pattern di backend, atau kondisi routing di frontend) — sehingga menambah kategori acara baru (mis. "Festival tanpa nomor kursi", "Workshop dengan kuota peserta") tidak memerlukan rombak total, cukup menambah flow baru untuk kategori tersebut.
- **Tidak perlu didesain detail sekarang** — cukup dipastikan struktur Event/EventSession tidak "mengunci" asumsi bahwa semua event pasti punya denah kursi, supaya ekstensi ke kategori lain di masa depan tidak butuh migrasi besar.

### Kebutuhan Fungsional
- FR-CAT1: Field `kategori_acara` wajib diisi admin saat membuat event.
- FR-CAT2: v1 sistem hanya benar-benar mengimplementasikan flow untuk kategori "Pertunjukan"; kategori lain bisa dicatat di database namun flow booking-nya belum tersedia (bisa ditampilkan sebagai "segera hadir" atau dinonaktifkan sementara di form admin sampai fase berikutnya dikembangkan).

---

## 17. Tech Stack & Implikasi Arsitektur

### 16.1 Stack yang Dipilih
| Layer | Teknologi |
|---|---|
| Backend framework | **Laravel 12** |
| Admin/Super Admin panel | **Filament 4.x** (resource, form, table builder) |
| Role & Permission (RBAC) | **Filament Shield** (`bezhansalleh/filament-shield` v4.x, berbasis `spatie/laravel-permission`) |
| Login publik (User) | Google OAuth + form manual (email/password) |

### 16.2 Implikasi Penting

**a. Filament pada dasarnya adalah admin panel, bukan halaman publik.**
Filament Shield mengatur akses ke *panel* Filament (resource, page, widget) — jadi paling pas dipakai untuk **dashboard Admin & Super Admin**. Halaman publik yang dipakai penonton (katalog event, pilih kursi, checkout) **bukan bagian dari panel Filament** — ini perlu dibangun terpisah, misalnya dengan Blade + Livewire (tetap dalam ekosistem Laravel yang sama) atau frontend terpisah yang mengonsumsi API Laravel.

**b. Role & Permission via Filament Shield**
- Shield otomatis generate permission untuk tiap Filament Resource (mis. `view_event`, `create_event`, `update_event`, `delete_event`, `approve_payment`, dst.) berdasarkan Resource/Page yang didefinisikan.
- Role **Admin** dan **Super Admin** dikelola lewat Shield's Role Resource, sudah selaras dengan matriks akses di Bagian 11.3 — Super Admin diberi semua permission, Admin diberi permission terbatas (semua fitur event/pembayaran, tapi TIDAK termasuk kelola venue & kelola akun admin, sesuai keputusan "semua admin setara" pada fitur event/pembayaran).
- Perlu custom Policy tambahan untuk aksi yang tidak otomatis ter-cover oleh Resource permission (mis. aksi "Approve Pembayaran" sebagai custom action di Filament, bukan CRUD standar) — di-generate manual sebagai permission custom di Shield.

**c. Login Google — perlu dipisah konteksnya:**
- **User (publik)**: login via Google OAuth menggunakan `laravel/socialite`, plus form registrasi/login manual dengan verifikasi email. Ini di luar panel Filament, jadi perlu diimplementasikan di layer aplikasi publik.
- **Admin/Super Admin (panel Filament)**: secara default Filament pakai email+password untuk masuk ke panel. Jika Admin/Super Admin juga ingin login via Google, perlu tambahan plugin seperti `dutchcodingcompany/filament-socialite` — **ini perlu dikonfirmasi apakah dibutuhkan atau cukup email+password untuk sisi admin.**

**d. Seat-lock & auto-release timer**
Mekanisme pelepasan kursi otomatis (saat timer habis / batas verifikasi custom per event terlewati) sebaiknya diimplementasikan dengan **Laravel Queue + Scheduled Job** (`php artisan schedule:run` atau job terjadwal dengan delay), bukan dicek manual saat halaman dibuka — supaya kursi benar-benar terlepas tepat waktu meski tidak ada user yang sedang mengakses.

**e. Penyimpanan bukti transfer**
Upload bukti transfer (gambar/PDF) disimpan lewat Laravel Filesystem (`local` disk untuk development, disarankan `s3`/object storage untuk production) dan ditampilkan di Filament Resource halaman approval pembayaran.

**f. Notifikasi**
Gunakan Laravel Notification (channel `mail` + `database`) untuk: konfirmasi pesanan masuk, hasil approve/reject pembayaran, reminder H-1 event.

### 16.3 Pertanyaan Teknis Tambahan (untuk tim dev)
1. Apakah Admin/Super Admin juga perlu login via Google, atau cukup email+password bawaan Filament?
2. Halaman publik (booking user) dibangun dengan Blade+Livewire (tetap satu project Laravel) atau frontend terpisah (mis. Vue/React) yang konsumsi REST/API dari Laravel?
3. Apakah perlu API terpisah untuk kemungkinan aplikasi mobile di masa depan, atau v1 cukup web saja?
