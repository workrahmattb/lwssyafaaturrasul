<div align="center">

# 🕌 LWS Videcode

### Platform Donasi & Wakaf — Lembaga Wakaf Syafa'aturrasul

Aplikasi web donasi wakaf dengan alur transfer manual yang terverifikasi admin, manajemen kampanye, laporan unit usaha, dan dashboard analitik.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-FB70A9?style=flat&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-10b981?style=flat)](LICENSE)

</div>

---

## 📖 Tentang Proyek

**LWS Videcode** adalah platform donasi/wakaf yang dibangun untuk **Lembaga Wakaf Syafa'aturrasul**. Platform ini menerima dua jenis donasi — **donasi umum** (Wakaf Pembangunan, Wakaf Produktif, Donasi Pendidikan) dan **donasi kampanye** spesifik — dengan alur pembayaran transfer bank manual yang diverifikasi oleh admin.

Donatur dapat memilih untuk tampil sebagai **"Hamba Allah"** (anonim), dan setiap donasi baru otomatis mengirim notifikasi email ke admin untuk diverifikasi.

## ✨ Fitur

### 🤝 Donasi Umum (3 Langkah)
- Pilih jenis wakaf: **Wakaf Pembangunan**, **Wakaf Produktif**, atau **Donasi Pendidikan**
- Input nominal dengan **format Rupiah otomatis** (`1.500.000`)
- Pilihan **donasi anonim** (tersimpan sebagai "Hamba Allah")
- Pilih rekening bank tujuan (grid card dengan logo bank)
- Ringkasan konfirmasi + **tombol salin nominal & nomor rekening**
- Upload **bukti transfer** (JPG/PNG/PDF, maks. 2MB)
- Halaman sukses dengan kartu donasi + kode transaksi unik (`TRX-XXXXXXXX`)
- Riwayat 5 donasi terbaru yang sudah disetujui

### 🎯 Donasi Kampanye
- Halaman daftar & detail kampanye dengan **progress bar real-time** (dihitung dari donasi yang disetujui)
- Alur donasi kampanye 3 langkah (pola yang sama dengan donasi umum)
- Validasi periode kampanye (tanggal mulai/selesai, status aktif)

### 🔐 Panel Admin
- **Login admin** terproteksi middleware (`is_admin`)
- **Dashboard analitik**: 4 kartu statistik, grafik tren donasi bulanan & tahunan (Chart.js, dipecah per tipe donasi), komposisi donasi per kategori (doughnut), donasi terbaru
- **Kelola Donasi Umum & Donasi Kampanye**: filter status, pencarian, lihat bukti transfer (modal), aksi **Approve / Reject / Delete**, pagination responsif
- **Kelola Kampanye**: CRUD lengkap dengan gambar, target, periode, dan status
- **Laporan Unit Usaha**: pencatatan pemasukan per unit + statistik & grafik (tren bulanan, komposisi per unit)
- **Export Excel (.xlsx)** untuk donasi umum, donasi kampanye, dan laporan unit usaha (PhpSpreadsheet, header berwarna + auto-size)
- **Notifikasi email** otomatis ke admin setiap ada donasi baru (queued)

### ⚡ Teknis
- **SPA navigation** dengan `wire:navigate` — tanpa full page reload
- Input nominal **auto-format ribuan** (Alpine.js) dengan cleanup aman di backend
- **Validasi per-langkah** pada form donasi
- Responsive penuh: tabel di desktop, card di mobile
- Deep delete yang rapi — bukti transfer & gambar kampanye ikut terhapus dari storage

## 🧱 Tech Stack

| Teknologi | Versi | Peran |
|---|---|---|
| [Laravel](https://laravel.com) | 12 | Framework backend |
| [Livewire](https://livewire.laravel.com) | 4 | Komponen reaktif full-stack |
| [Alpine.js](https://alpinejs.dev) | 3 | Interaksi UI ringan |
| [Tailwind CSS](https://tailwindcss.com) | 4 | Styling utility-first |
| [Chart.js](https://www.chartjs.org) | 4 | Grafik dashboard |
| [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io) | 5 | Export Excel .xlsx |
| [Vite](https://vite.dev) | 7 | Bundler aset |
| MySQL | 5.7+/8.0 | Database |

## 🚀 Instalasi

### Prasyarat
- PHP **≥ 8.2** dengan ekstensi `pdo_mysql`, `gd`, `zip`
- Composer
- Node.js **≥ 20** & npm
- MySQL

### Langkah Pemasangan

```bash
# 1. Clone repositori
git clone https://github.com/workrahmattb/lwssyafaaturrasul.git
cd lwssyafaaturrasul

# 2. Install dependency PHP & Node
composer install
npm install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di .env
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=lwsvidecode
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Jalankan migration + seeder (akun admin & rekening contoh)
php artisan migrate --seed

# 7. Buat symbolic link storage (untuk bukti transfer & gambar kampanye)
php artisan storage:link

# 8. Build aset frontend
npm run build
```

### Menjalankan Aplikasi

```bash
# Mode development (server + queue worker + Vite dalam satu perintah)
composer dev

# Atau manual:
php artisan serve
php artisan queue:listen --tries=1 --timeout=0   # wajib untuk kirim email
npm run dev

# Build produksi
npm run build
```

> ⚠️ **Penting:** Notifikasi email dikirim melalui queue. Pastikan `php artisan queue:listen` (atau queue worker lain) selalu berjalan di production, jika tidak email akan terkirim senyap.

### 👤 Akun Admin Default

Setelah menjalankan seeder:

| Email | Password |
|---|---|
| `admin@lwsvidecode.com` | `admin123` |

Akses panel admin di [`/admin/login`](http://localhost:8000/admin/login).

> 🔒 **Segera ganti password default ini** sebelum aplikasi dipakai di production (lihat tabel `users` atau seeder `AdminUserSeeder`).

### 📧 Konfigurasi Email (SMTP Gmail)

Panduan lengkap pembuatan App Password Gmail dan konfigurasi SMTP tersedia di [`GMAIL_SMTP_SETUP.md`](GMAIL_SMTP_SETUP.md). Ringkasannya, set di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email-anda@gmail.com
MAIL_PASSWORD=apppassword16karakter
MAIL_ENCRYPTION=tls

# Email admin penerima notifikasi donasi baru
MAIL_TO_ADMIN=email-admin@gmail.com
```

## 🗺️ Route Map

### Publik
| URL | Keterangan |
|---|---|
| `/` | Beranda — form donasi umum 3 langkah |
| `/kampanye` | Daftar kampanye aktif |
| `/kampanye/{slug}` | Detail kampanye + donatur terbaru |
| `/kampanye/{slug}/donasi` | Form donasi kampanye 3 langkah |

### Admin (middleware `admin`)
| URL | Keterangan |
|---|---|
| `/admin/login` | Login admin |
| `/admin/donations` | Dashboard utama |
| `/admin/manage` | Kelola donasi umum |
| `/admin/campaigns` | Kelola kampanye |
| `/admin/campaign-donation` | Kelola donasi kampanye |
| `/admin/unit-usaha` | Kelola laporan unit usaha |
| `/admin/export/donations` | Export Excel donasi umum |
| `/admin/export/campaign-donations` | Export Excel donasi kampanye |

## 🔄 Alur Donasi

```
┌─────────────────┐   ┌──────────────────────┐   ┌─────────────────────┐
│  Step 1         │   │  Step 2              │   │  Step 3             │
│  Isi Form       │──▶│  Konfirmasi &        │──▶│  Sukses             │
│  • Jenis wakaf  │   │  Upload Bukti        │   │  • Kartu donasi     │
│  • Nominal (Rp) │   │  • Ringkasan data    │   │  • Kode TRX         │
│  • Nama / WA    │   │  • Copy nominal      │   │  • Status: Pending  │
│  • Anonim       │   │  • Detail rekening   │   │  • Email → Admin    │
│  • Rekening     │   │  • Upload bukti      │   │                     │
└─────────────────┘   └──────────────────────┘   └─────────────────────┘
                                                              │
                              ┌───────────────────────────────┘
                              ▼
                     ┌──────────────────┐
                     │  Admin Verifikasi │
                     │  ✅ Approve       │
                     │  ❌ Reject        │
                     └──────────────────┘
```

## 🗄️ Struktur Database

| Tabel | Fungsi |
|---|---|
| `users` | Akun admin (flag `is_admin`) |
| `payment_methods` | Rekening bank tujuan (nama bank, no. rekening, a.n., logo) |
| `donations` | Donasi umum (tipe, nominal, bukti transfer, status, anonim) |
| `campaigns` | Kampanye (slug, target, periode, status, gambar, featured) |
| `campaign_donations` | Donasi kampanye |
| `unit_usaha_reports` | Laporan pemasukan unit usaha |

> 💡 Progress kampanye dihitung dari **donasi berstatus approved** (computed accessor), sehingga tidak ada double counting.

## 🧪 Testing

```bash
composer test
# atau per file:
php artisan test --filter=UnitUsahaReportManagerTest
php artisan test --filter=PaginationTest
php artisan test --filter=DonationFormNoBlurSyncTest
php artisan test --filter=LivewireSingleLoadTest
php artisan test --filter=AdminSpaNavigationTest
```

Test mencakup regression penting: alur create→edit laporan unit usaha (termasuk input format ribuan), pagination, form donasi tanpa race condition sync, single-load Livewire/Alpine, dan konsistensi SPA navigation.

## 🔧 Troubleshooting

<details>
<summary><b>UI tampil rusak / admin seolah tidak bisa edit</b></summary>

Kemungkinan besar ada file **`public/hot`** yang stale (artefak `npm run dev` yang masih menunjuk ke Vite dev server yang sudah mati). Semua aset gagal dimuat karena browser mencoba mengambil dari `http://[::1]:5173`.

```bash
rm public/hot      # hapus jika ada
npm run build      # lalu build ulang
```
</details>

<details>
<summary><b>Email tidak terkirim</b></summary>

- Pastikan queue worker berjalan: `php artisan queue:listen --tries=1 --timeout=0`
- Cek konfigurasi SMTP — lihat [`GMAIL_SMTP_SETUP.md`](GMAIL_SMTP_SETUP.md) (gunakan **App Password**, bukan password Gmail biasa)
- Jalankan `php artisan config:clear` setelah mengubah `.env`
</details>

## 📁 Struktur Proyek

```
├── app/
│   ├── Http/Controllers/Admin/   # AuthController, DashboardController, ExportController
│   ├── Livewire/                 # DonationFlow, CampaignList, CampaignDetail, CampaignDonate
│   │   └── Admin/                # CampaignManager, DonationManager, CampaignDonationManager, UnitUsahaReportManager
│   ├── Models/                   # User, Donation, Campaign, CampaignDonation, PaymentMethod, UnitUsahaReport
│   ├── Mail/                     # NewDonationMail, CampaignDonationMail (queued)
│   └── helpers.php               # formatRupiah(), formatRupiahCompact()
├── resources/views/
│   ├── livewire/                 # Views komponen Livewire
│   ├── livewire/admin/           # Views panel admin
│   ├── components/layouts/       # app, admin, campaign, guest
│   └── emails/                   # Template email notifikasi
├── database/migrations/
├── database/seeders/             # AdminUserSeeder, DatabaseSeeder
└── tests/Feature/                # Regression tests
```

## 🤝 Kontribusi

Kontribusi sangat kami hargai! Silakan:

1. **Fork** repositori ini
2. Buat branch fitur: `git checkout -b fitur/nama-fitur`
3. Commit perubahan: `git commit -m "feat: tambah fitur X"`
4. Push branch: `git push origin fitur/nama-fitur`
5. Buka **Pull Request**

Pastikan test tetap lolos sebelum submit PR (`composer test`).

## 🔒 Keamanan

Jika Anda menemukan celah keamanan, mohon laporkan langsung ke **[workrahmattb@gmail.com](mailto:workrahmattb@gmail.com)** — mohon jangan buat issue publik agar tidak dieksploitasi sebelum diperbaiki.

## 📄 License

Proyek ini dilisensikan di bawah lisensi **[MIT License](LICENSE)**.

Copyright &copy; 2026 [Rahmat Tanri Busra](mailto:workrahmattb@gmail.com)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) — Framework PHP yang elegan
- [Livewire](https://livewire.laravel.com) — Full-stack reactive components
- [Tailwind CSS](https://tailwindcss.com) — Utility-first CSS
- [Chart.js](https://www.chartjs.org) — Grafik dashboard
- [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io) — Export Excel

---

<div align="center">

**Dibuat dengan ❤️ untuk umat** — Lembaga Wakaf Syafa'aturrasul

⭐ Beri bintang di repositori ini jika bermanfaat!

</div>
