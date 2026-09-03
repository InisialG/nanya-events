# Spesifikasi Denah Kursi (Seat Map)

## Gambaran Umum

Denah kursi terbagi menjadi **3 zona** yang menghadap ke **Stage (panggung)**: **Kiri**, **Tengah**, dan **Kanan**. Setiap baris kursi diberi label huruf (huruf I, O, Q tidak digunakan — praktik umum di venue untuk menghindari kebingungan dengan angka 1 dan 0).

```
                    [ STAGE ]

   ZONA KIRI       ZONA TENGAH       ZONA KANAN
```

---

## Paket Tiket

Setiap kursi termasuk dalam salah satu dari 3 paket:

| Paket | Aturan |
|---|---|
| 🟣 **Diamond** | Semua kursi di **Zona Tengah** |
| 🟡 **Gold** | Default untuk semua kursi di **Zona Kiri** dan **Zona Kanan**, KECUALI kursi yang termasuk daftar Pink di bawah |
| 🩷 **Pink** | Kursi spesifik di Zona Kiri & Zona Kanan (baris C–H, dekat area tengah) — lihat daftar lengkap di bawah |

> **Logika penentuan paket:** Diamond ditentukan oleh zona (Tengah = Diamond, selalu). Gold vs Pink ditentukan per kursi individual di Zona Kiri/Kanan — jika kode kursinya ada di daftar Pink, paketnya Pink; jika tidak, default-nya Gold.

### Daftar Kursi Paket Pink (lengkap)

| Baris | Kursi Pink di Zona Kanan | Kursi Pink di Zona Kiri |
|---|---|---|
| C | 5, 6, 7 | 26, 27 |
| D | 4, 5, 6, 7 | 29, 30, 31, 32, 33 |
| E | 1, 2, 3, 4, 5, 6 | 32, 33, 34, 35 |
| F | 1, 2, 3, 4, 5 | 33, 34, 35, 36, 37 |
| G | 1, 2, 3, 4 | 35, 36, 37, 38, 39, 40 |
| H | 1, 2 | 36, 37, 38, 39, 40 |

Dalam format kode kursi (`{ZONA}-{BARIS}{NOMOR}`):

```
R-C05, R-C06, R-C07, L-C26, L-C27,
R-D04, R-D05, R-D06, R-D07, L-D29, L-D30, L-D31, L-D32, L-D33,
R-E01, R-E02, R-E03, R-E04, R-E05, R-E06, L-E32, L-E33, L-E34, L-E35,
R-F01, R-F02, R-F03, R-F04, R-F05, L-F33, L-F34, L-F35, L-F36, L-F37,
R-G01, R-G02, R-G03, R-G04, L-G35, L-G36, L-G37, L-G38, L-G39, L-G40,
R-H01, R-H02, L-H36, L-H37, L-H38, L-H39, L-H40
```

**Total kursi Pink: 51 kursi** (10 di Zona Kanan + 41 di Zona Kiri, tersebar di baris C sampai H)

### Rekap Jumlah Kursi per Paket

| Paket | Jumlah Kursi |
|---|---|
| 🟣 Diamond (Zona Tengah) | 334 |
| 🩷 Pink | 51 |
| 🟡 Gold (Kiri + Kanan, di luar Pink) | 213 |
| **Total** | **598** |

---

## Zona Kiri

| Baris | Rentang Nomor Kursi | Jumlah Kursi |
|---|---|---|
| A | 8–17 | 10 |
| B | 27–36 | 10 |
| C | 26–36 | 11 |
| D | 29–39 | 11 |
| E | 30–40 | 11 |
| F | 30–40 | 11 |
| G | 31–40 | 10 |
| H | 31–40 | 10 |
| J | 25–32 | 8 |
| K | 29–32 | 4 |
| L | 27–35 | 9 |
| M | 28–36 | 9 |
| N | 28–36 | 9 |
| P | 31–41 | 11 |
| R | 31–41 | 11 |
| S | 27–33 | 7 |
| T | 27–33 | 7 |

**Subtotal Zona Kiri: 159 kursi**

---

## Zona Tengah

| Baris | Rentang Nomor Kursi | Jumlah Kursi |
|---|---|---|
| B | 8–26 | 19 |
| C | 8–25 | 18 |
| D | 8–28 | 21 |
| E | 8–29 | 22 |
| F | 8–29 | 22 |
| G | 8–30 | 23 |
| H | 7–30 | 24 |
| J | 6–24 | 19 |
| K | 6–23 | 18 |
| L | 6–26 | 21 |
| M | 6–27 | 22 |
| N | 6–27 | 22 |
| P | 8–30 | 23 |
| R | 8–30 | 23 |
| S | 8–26 | 19 |
| T | 8–25 | 18 |

> **Catatan:** Baris **A** tidak memiliki kursi di Zona Tengah.

**Subtotal Zona Tengah: 334 kursi**

---

## Zona Kanan

| Baris | Rentang Nomor Kursi | Jumlah Kursi |
|---|---|---|
| A | 1–7 | 7 |
| B | 1–7 | 7 |
| C | 1–7 | 7 |
| D | 1–7 | 7 |
| E | 1–7 | 7 |
| F | 1–7 | 7 |
| G | 1–7 | 7 |
| H | 1–6 | 6 |
| J | 1–5 | 5 |
| K | 1–5 | 5 |
| L | 1–5 | 5 |
| M | 1–5 | 5 |
| N | 1–5 | 5 |
| P | 1–7 | 7 |
| R | 1–7 | 7 |
| S | 1–7 | 7 |
| T | 1–7 | 7 |

**Subtotal Zona Kanan: 105 kursi**

---

## Total Keseluruhan

| Zona | Jumlah Kursi |
|---|---|
| Kiri | 159 |
| Tengah | 334 |
| Kanan | 105 |
| **Total** | **598** |

---

## Format Kode Kursi (untuk Database/Seeder)

Format yang disarankan untuk menghindari bentrok antar zona:

```
{ZONA}-{BARIS}{NOMOR}
```

Dengan:
- `ZONA` = `L` (Left/Kiri), `C` (Center/Tengah), `R` (Right/Kanan)
- `BARIS` = huruf baris (A, B, C, ... T)
- `NOMOR` = nomor kursi, disarankan 2 digit dengan leading zero (contoh: `08`, `27`)

**Contoh kode kursi:**
- `L-A08` → Zona Kiri, baris A, kursi nomor 8
- `C-B27` → Zona Tengah, baris B, kursi nomor 27
- `R-H03` → Zona Kanan, baris H, kursi nomor 3

> Format alternatif tanpa prefix zona (`{BARIS}{NOMOR}`, contoh `B27`) **tidak disarankan** karena berisiko ambigu antar zona meskipun secara kebetulan rentang nomornya di dokumen ini tidak saling tumpang tindih — prefix zona tetap membuat data lebih aman dan mudah dibaca.

### Kolom Tambahan untuk Seeder

Saat generate data seat di database, tambahkan kolom `package` (`diamond` / `gold` / `pink`) dengan logika:

```
IF zona == "Tengah"          -> package = "diamond"
ELSE IF kode ada di daftar Pink -> package = "pink"
ELSE                          -> package = "gold"
```

---

## Referensi Cepas per Baris (Rentang Nomor Gabungan)

| Baris | Kiri | Tengah | Kanan |
|---|---|---|---|
| A | 8–17 | – | 1–7 |
| B | 27–36 | 8–26 | 1–7 |
| C | 26–36 | 8–25 | 1–7 |
| D | 29–39 | 8–28 | 1–7 |
| E | 30–40 | 8–29 | 1–7 |
| F | 30–40 | 8–29 | 1–7 |
| G | 31–40 | 8–30 | 1–7 |
| H | 31–40 | 7–30 | 1–6 |
| J | 25–32 | 6–24 | 1–5 |
| K | 29–32 | 6–23 | 1–5 |
| L | 27–35 | 6–26 | 1–5 |
| M | 28–36 | 6–27 | 1–5 |
| N | 28–36 | 6–27 | 1–5 |
| P | 31–41 | 8–30 | 1–7 |
| R | 31–41 | 8–30 | 1–7 |
| S | 27–33 | 8–26 | 1–7 |
| T | 27–33 | 8–25 | 1–7 |
