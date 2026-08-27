# PRD: Pengujian Skenario Flash Sale & Lonjakan Trafik (Spike Testing)

**Dokumen:** Panduan & Skenario Pengujian Performa Nanya Events
**Fokus:** Simulasi *Ticket Rush*, *Concurrent Checkout*, dan *Race Conditions*
**Arsitektur Saat Ini:** Monolith (Laravel 12), Database MySQL, Frontend Livewire/Blade, Queue System (Database/Redis).

---

## 1. Latar Belakang & Tujuan Pengujian

Pada saat peluncuran tiket event pentas seni yang sangat populer, sistem Nanya Events berisiko menghadapi lonjakan trafik (*spike*) yang sangat ekstrem dalam hitungan detik. Pengujian beban biasa (*load testing* dengan *ramp-up* perlahan) tidak cukup untuk merepresentasikan perilaku pengguna asli di mana ribuan orang melakukan "Hit" ke halaman "Pilih Kursi" dan "Checkout" di detik yang persis sama.

**Tujuan Pengujian:**
1. Memastikan tidak ada **overselling** (satu kursi tidak boleh terkunci atau terjual kepada lebih dari satu pengguna).
2. Menguji keandalan sistem *locking* kursi (pencegahan *race condition*).
3. Menemukan *bottleneck* (titik kemacetan) utama pada arsitektur sistem saat ini.

---

## 2. Skenario Pengujian Wajib (Mandatory Scenarios)

### 2.1. Simulasi Flash Sale / Ticket Rush (Spike Mendadak)
**Deskripsi**: Mensimulasikan ribuan pengguna mengakses halaman denah kursi dan melakukan pemesanan (mengklik "Pilih Kursi" & "Checkout") secara bersamaan di detik yang sama, bukan perlahan-lahan.
**Target Evaluasi**: 
- Apakah server (Web & Database) langsung *crash* atau *timeout* (HTTP 500/504)?
- Berapa lama respons dari komponen Livewire saat merender denah kursi dengan beban berat?

### 2.2. Concurrent Checkout pada Ketersediaan Terbatas (Race Condition Test)
**Deskripsi**: Mensimulasikan 1.000 pengguna mencoba mengunci/membeli sisa tiket yang sangat terbatas secara serentak (contoh: 100 kursi tersisa).
**Target Evaluasi**:
- **Overselling**: Validasi apakah jumlah kursi yang terjual atau terkunci (*locked*) melebihi stok yang ada (100 kursi).
- **Row-Locking**: Memastikan transaksi database (seperti `DB::transaction` dan `lockForUpdate()`) berjalan dengan benar tanpa menyebabkan status *deadlock* pada tabel `seat_availabilities`.
- **Waktu Tunggu**: Mengetahui berapa lama pengguna tertahan saat menekan tombol checkout sebelum mendapat respons gagal (kursi sudah diambil orang lain) atau berhasil.

### 2.3. Simulasi Antrean (Queue / Waiting Room Test) - *Jika Diimplementasikan*
**Deskripsi**: Jika ke depannya sistem mengadopsi ruang tunggu virtual (seperti tiket.com/loket.com), tes ini bertujuan memastikan sistem *queue* berjalan adil (First-In First-Out) dan stabil.
**Target Evaluasi**: Tidak ada pengguna yang memotong antrean, dan sistem pendaftaran antrean (berbasis Redis) tidak *crash*.

### 2.4. Simulasi API / Gateway Bottleneck (Future-Proofing)
**Deskripsi**: Karena Nanya Events saat ini menggunakan metode transfer bank manual dengan *upload* bukti bayar, bottleneck yang dievaluasi adalah **proses *upload* file** (I/O Disk) secara massal. Jika ke depan menggunakan *Payment Gateway* (Midtrans/Xendit), maka fokus pengujian adalah antrean API call yang sinkron.
**Target Evaluasi**: Apakah sistem tetap responsif meski proses upload massal terjadi bersamaan.

---

## 3. Titik Rawan Bottleneck di Nanya Events

Karena sistem Nanya Events berbasis **Monolith**, berikut adalah titik-titik yang kemungkinan besar akan mencapai batasnya (*mentok*) lebih dulu:

1. **Database Row-Locking (Titik Paling Kritis)**
   Saat ratusan pengguna berebut 1 kursi (misal A-10), query `UPDATE seat_availabilities SET status = 'locked'` akan saling memblokir jika tidak menggunakan Redis atau mekanisme *optimistic/pessimistic locking* yang efisien.
2. **Session / Cache Server**
   Penyimpanan state session pengguna dan session *locked seats* di Livewire. Jika masih menggunakan file-based session, I/O disk akan sangat lambat. (Sangat disarankan menggunakan **Redis** untuk *Session* dan *Cache*).
3. **Overhead Livewire Component**
   Saat 1.000 user memuat komponen visual denah kursi, setiap interaksi klik akan memicu *AJAX request*. Ini bisa membebani memori PHP-FPM.

---

## 4. Metrik Kunci untuk Dipantau (Key Metrics)

Saat skenario dijalankan, pantau titik-titik berikut secara *real-time*:
- ⏱️ **Response Time**: Untuk *endpoint* render denah dan aksi *checkout*.
- ❌ **Error Rate**: Persentase *request* yang mengembalikan HTTP 500 (Internal Server Error) atau 504 (Gateway Timeout).
- 🎟️ **Integritas Data Kursi**: Jumlah pesanan valid vs jumlah kursi yang seharusnya dikunci/terjual.
- 💻 **Resource Server (CPU/RAM)**: Terutama beban CPU pada database MySQL ketika melakukan operasi `SELECT ... FOR UPDATE` secara masif.

---

## 5. Rekomendasi Tools & Skrip Pengujian

### Tools Utama: `k6`
`k6` adalah *tool* berbasis JavaScript yang paling direkomendasikan untuk *Spike Testing* karena kemampuannya menghasilkan ribuan *Virtual Users (VUs)* secara asinkron dengan konfigurasi *arrival-rate* yang akurat. 

*(Alternatif: Artillery)*

### Contoh Skrip `k6` untuk Spike Test (Simulasi Pemilihan Kursi Nanya Events)

Simpan skrip di bawah ini sebagai `spike-test.js` dan jalankan dengan `k6 run spike-test.js`.

```javascript
import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  scenarios: {
    ticket_rush: {
      executor: 'ramping-arrival-rate',
      startRate: 0,
      timeUnit: '1s',
      preAllocatedVUs: 3000,
      stages: [
        { target: 500, duration: '5s' },    // Lonjakan cepat awal
        { target: 1500, duration: '10s' },  // Puncak tertinggi flash sale
        { target: 1500, duration: '30s' },  // Bertahan di puncak beban
        { target: 0, duration: '10s' },     // Penurunan setelah tiket habis
      ],
    },
  },
};

// Asumsi: Endpoint API / Livewire untuk booking kursi
export default function () {
  const url = 'https://events.nanyang.sch.id/api/checkout-simulation';
  const payload = JSON.stringify({
    event_session_id: 1, // ID Sesi Event
    seat_id: Math.floor(Math.random() * 120) + 1, // Memilih kursi secara acak (1-120)
  });

  const params = {
    headers: {
      'Content-Type': 'application/json',
      // Tambahkan header CSRF atau Bearer token jika diperlukan
    },
  };

  const response = http.post(url, payload, params);

  // Verifikasi apakah transaksi lolos, kursi habis, atau gagal karena error
  check(response, {
    'status is 200 (Success)': (r) => r.status === 200,
    'status is 409 (Seat taken)': (r) => r.status === 409,
    'status is 500 (Server Error)': (r) => r.status === 500,
  });
}
```

---
*Dokumen ini dirumuskan berdasarkan permintaan kebutuhan pengujian spesifik untuk arsitektur Nanya Events guna mempersiapkan platform menghadapi traffic masif sesungguhnya.*
