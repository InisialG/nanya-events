# 📚 Dokumentasi Platform Tiket & Booking Kursi Pentas Seni

Selamat datang di pusat dokumentasi project **Nanya Events** (Platform Tiket & Booking Kursi Event Pentas Seni).

Dokumentasi ini dirancang agar siapapun — baik developer maupun AI Agent (termasuk session baru) — dapat **langsung memahami konteks, tujuan, arsitektur, status pengerjaan, dan riwayat session** hanya dalam satu kali baca.

---

## 🚀 Quick Navigation

| Dokumen | Deskripsi | Target Pembaca |
|---|---|---|
| 📌 [**PRD (Product Requirement Document)**](file:///e:/laragon/www/nanya-events/docs/PRD-Platform-Tiket-Event-Pentas-Seni.md) | Spesifikasi kebutuhan produk lengkap (v0.2 draft baseline). | PM, Lead Dev, AI Agent |
| 🎯 [**00-PROJECT-OVERVIEW.md**](file:///e:/laragon/www/nanya-events/docs/00-PROJECT-OVERVIEW.md) | Ringkasan visi produk, tujuan utama, pengguna, dan fitur kunci. | All |
| 🏗️ [**01-ARCHITECTURE-DATABASE.md**](file:///e:/laragon/www/nanya-events/docs/01-ARCHITECTURE-DATABASE.md) | Arsitektur teknis, tech stack, skema database, RBAC, dan alur data. | Backend, Frontend, AI Agent |
| 🗺️ [**02-ROADMAP-TASKS.md**](file:///e:/laragon/www/nanya-events/docs/02-ROADMAP-TASKS.md) | Breakdown fase pengerjaan dan checklist tugas spesifik. | Dev, PM, AI Agent |
| 🛠️ [**03-DEVELOPMENT-GUIDE.md**](file:///e:/laragon/www/nanya-events/docs/03-DEVELOPMENT-GUIDE.md) | Panduan instalasi, alur kerja, standar koding, dan aturan pembaruan session. | Developer, AI Agent |
| 🕒 [**SESSION-LOG.md**](file:///e:/laragon/www/nanya-events/docs/SESSION-LOG.md) | **Memory & Log Session** — Status terkini, apa yang baru selesai, dan instruksi session berikutnya. | **AI Agent (Wajib Baca Pertama Kali)** |

---

## ⚡ Panduan Cepat untuk AI Agent / Session Baru

Jika Anda adalah AI Agent yang baru memulai session pada repository ini:

1. **Baca [SESSION-LOG.md](file:///e:/laragon/www/nanya-events/docs/SESSION-LOG.md)** terlebih dahulu untuk melihat **Status Terkini** dan **Tugas Terakhir yang Selesai**.
2. **Baca [00-PROJECT-OVERVIEW.md](file:///e:/laragon/www/nanya-events/docs/00-PROJECT-OVERVIEW.md)** & **[01-ARCHITECTURE-DATABASE.md](file:///e:/laragon/www/nanya-events/docs/01-ARCHITECTURE-DATABASE.md)** untuk memahami alur aplikasi & struktur database.
3. Kerjakan tugas sesuai dengan **[02-ROADMAP-TASKS.md](file:///e:/laragon/www/nanya-events/docs/02-ROADMAP-TASKS.md)**.
4. **SETIAP KALI SELESAI MENGERJAKAN TUGAS**: Wajib perbarui `[SESSION-LOG.md](file:///e:/laragon/www/nanya-events/docs/SESSION-LOG.md)` dengan mencatat apa yang telah diselesaikan, perubahan file, dan status terkini.

---

## 🎯 Konsep Utama Platform

1. **Reusable Venue Layout**: Denah venue & posisi kursi dibuat 1x oleh Super Admin dan dipakai berulang kali untuk berbagai event pentas seni tanpa perlu desain ulang denah.
2. **Harga per Kategori Kursi di Venue**: Harga tiket melekat pada kategori kursi di venue (mis. VIP, Reguler, Balkon), sehingga Admin tidak perlu input harga tiap kali membuat event baru.
3. **Seat Availability per Event & Session**: Status ketersediaan kursi (`tersedia`, `terkunci`, `terjual`) di-clone per `EventSession`.
4. **Manual Transfer + Admin Approval**: Penonton memesan kursi → memilih metode transfer bank → mengunggah bukti transfer → Admin memverifikasi/approve → E-Tiket QR diterbitkan.
