# 🎯 Project Overview: Platform Tiket & Booking Kursi Pentas Seni

## 1. Ringkasan Eksekutif

**Nanya Events** adalah platform ticketing dan pemesanan kursi interaktif yang dirancang khusus untuk event pentas seni (pertunjukan tari, teater, konser musik, pertunjukan budaya, dll.). 

Platform ini memecahkan masalah utama penyelenggara event di venue tetap: **efisiensi pengelolaan event dan denah kursi**. Dengan platform ini, denah venue dan kategori harga kursi cukup didesain sekali, lalu dapat digunakan secara berulang (reusable) untuk berbagai event dan sesi acara.

---

## 2. Tujuan & Visi Produk

### Tujuan Utama
1. **Kemudahan Admin**: Admin dapat membuat dan menerbitkan event baru dalam hitungan menit tanpa perlu menyusun/mengisi ulang denah kursi atau mengatur harga satu per satu.
2. **Pengalaman Penonton Modern**: Penonton dapat melihat denah kursi secara visual, memilih kursi spesifik secara real-time, mengunci kursi sementara saat transaksi, dan menerima e-tiket ber-QR code.
3. **Sistem Pembayaran Manual Terkontrol**: Mendukung alur pembayaran transfer bank manual dengan pengunggahan bukti transfer dan sistem verifikasi/approval yang aman oleh Admin.

### Metrik Keberhasilan (KPI)
- ⚡ **Waktu Pembuatan Event**: ≤ 5 menit dari upload poster hingga publish.
- 🎯 **Conversion Rate**: ≥ 30% penonton yang membuka detail event menyelesaikan pembayaran.
- ⏱️ **Waktu Checkout User**: ≤ 3 menit untuk memilih kursi hingga menyelesaikan instruksi bayar.
- 🔒 **Zero Double-Booking**: 0% kejadian dua penonton berhasil membeli kursi yang sama di sesi yang sama.

---

## 3. Matriks Peran & Hak Akses Pengguna (RBAC)

 Sesuai keputusan spesifikasi (PRD v0.2), sistem dibagi menjadi 3 peran:

| Fitur / Modul | User (Penonton) | Admin (Panitia) | Super Admin |
|---|:---:|:---:|:---:|
| **Autentikasi (Google OAuth & Email/Password)** | ✅ | ✅ | ✅ |
| **Browse Event & Detail Sesi** | ✅ | ✅ | ✅ |
| **Pilih Kursi & Booking (Seat Lock)** | ✅ | — | — |
| **Upload Bukti Transfer Bank** | ✅ | — | — |
| **Lihat E-Tiket & Riwayat Pesanan** | ✅ | — | — |
| **Manajemen Event (CRUD Poster, Deskripsi, Sesi)** | — | ✅ | ✅ |
| **Verifikasi Pembayaran (Approve / Reject)** | — | ✅ | ✅ |
| **Lihat Laporan Penjualan per Event** | — | ✅ | ✅ |
| **Kelola Rekening Bank Tujuan Transfer** | — | ✅ | ✅ |
| **Kelola Venue & Denah Kursi Master** | — | — | ✅ |
| **Kelola Akun Admin (Tambah/Hapus/Nonaktifkan)** | — | — | ✅ |
| **Log Audit System** | — | — | ✅ |

> **Catatan Penting**: Semua Admin setara pada penanganan event dan pembayaran (Admin A bisa verifikasi pembayaran untuk event yang dibuat Admin B).

---

## 4. Alur Kerja Utama (Core Workflows)

### A. Alur Admin: Pembuatan Event Baru
```
1. Login Admin Panel (Filament)
   ↓
2. Buat Event Baru:
   - Upload Poster (Format 3:4)
   - Isi Judul, Kategori Acara ("Pertunjukan"), Deskripsi
   - Pilih Venue (Default: Venue Utama)
   - Atur Batas Waktu Verifikasi Pembayaran (misal: 12 jam / 24 jam)
   ↓
3. Atur Sesi / Jadwal (Tanggal & Jam Mulai/Selesai)
   ↓
4. Preview Event & Rentang Harga (Otomatis terhitung dari Seat Category Venue)
   ↓
5. Publish Event → Tampil di Katalog Publik
```

### B. Alur Penonton: Booking Kursi & Pembayaran
```
1. Browse Katalog Event publik → Pilih Event & Sesi Acara
   ↓
2. Login (Wajib sebelum masuk ke Denah Kursi)
   ↓
3. Lihat Denah Kursi Venue → Pilih Kursi yang Tersedia (Seat Lock Active + Timer Countdown)
   ↓
4. Checkout → Tampil Ringkasan Order & Detail Rekening Bank Transfer
   ↓
5. Penonton Transfer Manual via Bank/Mobile Banking
   ↓
6. Penonton Upload Bukti Transfer (Foto/PDF)
   ↓
7. Status Order: "Menunggu Verifikasi" (Kursi tetap terkunci hingga batas waktu custom event)
   ↓
8. Admin Verifikasi di Dashboard Filament:
   ├── APPROVE ➔ Order Lunas ➔ E-Tiket (QR Code) Terbit
   └── REJECT  ➔ Penonton Diinfokan Alasan ➔ Kursi Dilepas Kembali
```

---

## 5. Ruang Lingkup Sistem (System Scope)

### In-Scope (Fase 1 / Release v1)
- Platform Web Responsive (Desktop & Mobile Browser).
- Single Venue Master dengan denah kursi kustom reusable (VIP, Reguler, Balkon, dll.).
- Multi-Event & Multi-Session per Event.
- Pembayaran Transfer Bank Manual + Upload Bukti Transfer + Approval Admin.
- E-Tiket dengan QR Code unik per kursi + status scan validasi.
- Dashboard Admin & Super Admin berbasis **Filament 4.x**.

### Out-of-Scope (Fase Selanjutnya)
- Multi-venue dengan denah berbeda-beda.
- Automatic Payment Gateway real-time (Midtrans/Xendit) — *v1 difokuskan pada manual transfer bank*.
- Penjualan Merchandise / F&B tambahan.
- Transfer tiket antar pengguna.
