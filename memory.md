# Project Memory — Lembaga Wakaf Syafa'aturrasul (LWS)

## 📌 Overview

**Platform**: Website donasi/wakaf berbasis web untuk Lembaga Wakaf Syafa'aturrasul.

**Tujuan**: Menerima donasi umum (Wakaf Pembangunan, Wakaf Produktif, Donasi Pendidikan) dan donasi kampanye spesifik, dengan sistem verifikasi admin.

**Tech Stack**:
- **Framework**: Laravel 12
- **Frontend**: Livewire 4 + Alpine.js + Tailwind CSS 4
- **Database**: MySQL
- **SPA Navigation**: `wire:navigate` (Livewire)
- **Email**: Laravel Mail (SMTP/Gmail)
- **Export**: PhpSpreadsheet (Excel .xlsx)
- **Build**: Vite + Laravel Vite Plugin

---

## 🗺️ Route Map

### Public Routes
| URL | Component/Controller | Nama Route | Keterangan |
|---|---|---|---|
| `/` | `DonationFlow` | `donation` | Beranda — form donasi umum 3-step |
| `/kampanye` | `CampaignList` | `campaigns.index` | Grid kampanye aktif |
| `/kampanye/{slug}` | `CampaignDetail` | `campaigns.detail` | Detail kampanye + donasi |
| `/kampanye/{slug}/donasi` | `CampaignDonate` | `campaigns.donate` | Form donasi kampanye 3-step |

### Admin Routes (middleware: `admin`)
| URL | Component/Controller | Nama Route | Keterangan |
|---|---|---|---|
| `/admin/login` | `AuthController` | `admin.login` | Login admin (GET + POST) |
| `/admin/logout` | `AuthController` | `admin.logout` | Logout (POST) |
| `/admin/donations` | `DashboardController@dashboard` | `admin.donations` | Dashboard utama |
| `/admin/manage` | `DashboardController@manage` | `admin.manage` | Kelola donasi umum |
| `/admin/campaigns` | `CampaignManager` | `admin.campaigns` | Kelola kampanye |
| `/admin/campaign-donation` | `CampaignDonationManager` | `admin.campaign-donation` | Kelola donasi kampanye |
| `/admin/unit-usaha` | `UnitUsahaReportManager` | `admin.unit-usaha` | Kelola laporan unit usaha |
| `/admin/export/donations` | `ExportController@exportDonations` | `admin.export.donations` | Export Excel donasi umum |
| `/admin/export/campaign-donations` | `ExportController@exportCampaignDonations` | `admin.export.campaign-donations` | Export Excel donasi kampanye |

---

## 🗄️ Database Schema

### `users`
| Kolom | Type | Keterangan |
|---|---|---|
| id | bigint (PK) | |
| name | string | |
| email | string (unique) | |
| password | string (hashed) | |
| is_admin | boolean (added later) | Flag admin |
| remember_token | string | |
| timestamps | | |

### `payment_methods`
| Kolom | Type | Keterangan |
|---|---|---|
| id | bigint (PK) | |
| bank_name | string | Contoh: BSI, BCA |
| account_number | string | Nomor rekening |
| account_name | string | Atas nama |
| logo | string (nullable) | Path logo bank |
| timestamps | | |

### `donations` (Donasi Umum)
| Kolom | Type | Keterangan |
|---|---|---|
| id | bigint (PK) | |
| trx_id | string (unique) | TRX-XXXXXXXX |
| type | enum | `wakaf_pembangunan`, `wakaf_produktif`, `donasi_pendidikan` |
| donatur_name | string | Default: 'Hamba Allah' |
| phone | string (nullable) | |
| amount | decimal(15,2) | |
| payment_method_id | FK → payment_methods | |
| proof_of_transfer | string (nullable) | Path file bukti |
| status | enum | `pending`, `approved`, `rejected` |
| is_anonymous | boolean | |
| timestamps | | |

### `campaigns`
| Kolom | Type | Keterangan |
|---|---|---|
| id | bigint (PK) | |
| title | string | |
| slug | string (unique) | Auto dari Str::slug |
| description | text | |
| short_description | text (nullable) | |
| target_amount | decimal(15,2) | Target donasi |
| raised_amount | decimal(15,2) | Default 0 |
| start_date | date (nullable) | |
| end_date | date (nullable) | |
| status | enum | `draft`, `active`, `completed`, `cancelled` |
| image | string (nullable) | Path gambar |
| donor_count | integer | Default 0 |
| is_featured | boolean | |
| timestamps | | |

### `campaign_donations` (Donasi Kampanye)
| Kolom | Type | Keterangan |
|---|---|---|
| id | bigint (PK) | |
| trx_id | string (unique) | |
| campaign_id | FK → campaigns (cascade) | |
| donatur_name | string | |
| phone | string | |
| amount | decimal(15,2) | |
| payment_method_id | FK → payment_methods | |
| proof_of_transfer | string (nullable) | |
| status | enum | `pending`, `approved`, `rejected` |
| is_anonymous | boolean | |
| timestamps | | |

### `unit_usaha_reports` (Laporan Unit Usaha)
| Kolom | Type | Keterangan |
|---|---|---|
| id | bigint (PK) | |
| nama | string | Nama laporan/unit |
| tanggal | date | |
| jumlah_uang | decimal(15,2) | |
| catatan | text (nullable) | |
| timestamps | | |

---

## 🔄 Alur Donasi Umum (3-Step)

### Step 1: Isi Form (`/`)
1. Pilih **jenis wakaf** (3 opsi: Wakaf Pembangunan, Wakaf Produktif, Donasi Pendidikan)
2. Input **nominal donasi** (dengan formatting Rupiah otomatis)
3. Input **nama donatur** (disabled jika anonim)
4. Input **nomor WhatsApp** (hanya angka)
5. Toggle **anonim** (checkbox custom)
6. Pilih **rekening bank tujuan** (grid card dengan logo)
7. Klik **"Lanjutkan Pembayaran"** → validasi → Step 2

### Step 2: Konfirmasi & Upload
1. Lihat **ringkasan data** (jenis, nominal, nama, WA, bank)
2. **Copy nominal** ke clipboard (tombol salin)
3. Lihat detail **rekening tujuan** (nama bank, no rekening, a.n.)
4. **Upload bukti transfer** (JPG/PNG/PDF, max 2MB)
5. Klik **"Konfirmasi Pembayaran"** → validasi → simpan → Step 3

### Step 3: Sukses
1. Tampilkan **"Jazakumullah Khairan"** + icon centang
2. Tampilkan **kartu donasi** (nama, jenis, nominal, kode TRX, tanggal)
3. Tampilkan info rekening bank + tombol **salin no rekening**
4. Tampilkan status **"Pending - akan diverifikasi admin"**
5. Tombol: **Donasi Lagi** | **Kembali ke Beranda**
6. **Riwayat donasi terbaru** (tabel 5 data terakhir yang sudah approved)
7. **Notifikasi email** dikirim ke admin (NewDonationMail)

### Komponen: `DonationFlow`
- File: `app/Livewire/DonationFlow.php`
- Layout: `donation` (custom layout langsung di blade `donation.blade.php`)
- View: `resources/views/livewire/donation-flow.blade.php`
- Fitur: `WithFileUploads`, validasi per-step, formatting angka

---

## 🔄 Alur Donasi Kampanye (3-Step)

### Step 1: Isi Form (`/kampanye/{slug}/donasi`)
1. Input **nominal donasi** (format Rupiah)
2. Input **nama donatur** (disabled jika anonim)
3. Input **nomor WhatsApp**
4. Toggle **anonim** (toggle card custom)
5. Pilih **rekening bank tujuan** (grid card)
6. Klik **"Lanjutkan Pembayaran"** → validasi → Step 2

### Step 2: Konfirmasi & Upload
1. Lihat **kode transaksi** (generated)
2. Ringkasan: kampanye, donatur, WA, nominal (dengan tombol copy)
3. Info **bank tujuan** + copy no rekening
4. **Upload bukti transfer**
5. Klik **"Konfirmasi Pembayaran"** → validasi → simpan → Step 3

### Step 3: Sukses
1. **"Alhamdulillah! 🎉"** + icon centang
2. Kartu donasi (kampanye, nominal, kode TRX, tanggal)
3. Info rekening bank + **salin no rekening**
4. Status **Pending - akan diverifikasi admin**
5. Tombol: **Kembali ke Kampanye** | **Donasi Lagi**
6. **Notifikasi email** ke admin (CampaignDonationMail)

### Komponen: `CampaignDonate`
- File: `app/Livewire/CampaignDonate.php`
- Layout: `components.layouts.campaign`
- View wrapper: `resources/views/livewire/campaign-donate.blade.php` (progress steps)
- View form: `resources/views/livewire/campaign-donate-form.blade.php` (3-step content)
- Fitur: `WithFileUploads`, validasi, notifikasi email

> **Catatan**: `CampaignDonate` adalah satu-satunya komponen aktif untuk donasi kampanye. Komponen duplikat (`CampaignDonationForm`, `CampaignDonationFlow`) sudah dihapus.

---

## 🔐 Alur Admin

### Login
1. Akses `/admin/login`
2. Form email + password + remember me
3. Validasi: `is_admin` harus `true`
4. Redirect ke `/admin/donations` (dashboard)

### Middleware: `AdminMiddleware`
- Cek `auth()->user()->is_admin`
- Jika tidak admin → redirect ke `/admin/login`
- Dipasang di semua route `/admin/*` kecuali login

### Dashboard (`/admin/donations`)
- **4 Kartu Statistik**: Total Donasi, Total Terkumpul, Menunggu Verifikasi, Disetujui
- **Grafik Tren Donasi Perbulan** (12 bulan, bar chart Chart.js) — dipecah per **tipe donasi**: Wakaf Pembangunan, Wakaf Produktif, Donasi Pendidikan
- **Grafik Tren Donasi Pertahun** (5 tahun, bar chart Chart.js) — dipecah per tipe donasi
- **Donasi per Kategori** (progress bar: Wakaf Pembangunan, Produktif, Pendidikan + doughnut komposisi)
- **Donasi Terbaru** (tabel 5 data terakhir)
- **Laporan Unit Usaha**:
  - 4 kartu statistik (total laporan, total uang, rata-rata, jumlah unit)
  - **Grafik Tren Pemasukan Perbulan** (bar chart, Chart.js) — sum `jumlah_uang` per bulan dari semua laporan
  - **Grafik Komposisi per Unit Usaha** (doughnut chart, Chart.js) — sum per `nama` unit usaha
  - 5 laporan terbaru
- **Quick Actions**: Kelola Kampanye, Laporan Unit Usaha, Kelola Donasi, Lihat Halaman Donasi

### Kelola Donasi Umum (`/admin/manage`)
- Filter status: Semua / Pending / Approved / Rejected
- Search by nama donatur
- Tabel desktop + card mobile (responsive)
- Aksi: **Approve** | **Reject** | **Delete**
- Lihat bukti transfer (modal image)
- Export CSV (Excel)
- Pagination (10 per page)

### Kelola Kampanye (`/admin/campaigns`)
- Grid card kampanye (image, title, progress, amount, status, donor count)
- **Buat Baru** (modal form): title, description, short_description, target_amount, start_date, end_date, status, image, is_featured
- **Edit** (modal form sama)
- **Delete** dengan konfirmasi
- Pagination (10 per page)

### Kelola Donasi Kampanye (`/admin/campaign-donation`)
- Sama seperti donasi umum tapi dengan kolom kampanye
- Approve → increment `raised_amount` + `donor_count` di campaign
- Reject/Delete → decrement jika status sebelumnya approved
- Filter + Search + Export + Pagination

### Laporan Unit Usaha (`/admin/unit-usaha`)
- **3 Kartu Statistik**: Total Laporan, Total Uang, Rata-rata
- Tabel desktop + card mobile
- **Tambah Laporan** (modal): Nama, Tanggal, Jumlah Uang, Catatan
- **Input Jumlah Uang** berformat ribuan otomatis (`1.500.000`) — pola sama dengan form donasi, di-cleanup via `updatedJumlahUang()` + `preg_replace` sebelum simpan
- **Edit** + **Delete** dengan konfirmasi
- Pagination (15 per page)
- SPA navigation dengan `wire:navigate`

---

## 📁 Struktur File Penting

### Models (`app/Models/`)
| File | Keterangan |
|---|---|
| `User.php` | Admin user dengan `is_admin` |
| `Donation.php` | Donasi umum, relasi ke PaymentMethod |
| `Campaign.php` | Kampanye, relasi ke CampaignDonation, computed attributes: `progress`, `total_approved_amount`, `total_approved_donors` |
| `CampaignDonation.php` | Donasi kampanye, relasi ke Campaign + PaymentMethod |
| `PaymentMethod.php` | Rekening bank, relasi ke Donations |
| `UnitUsahaReport.php` | Laporan unit usaha |

### Livewire Components (`app/Livewire/`)
| File | Keterangan |
|---|---|
| `DonationFlow.php` | Form donasi umum 3-step |
| `CampaignList.php` | Daftar kampanye publik |
| `CampaignDetail.php` | Detail kampanye + donatur terbaru |
| `CampaignDonate.php` | Form donasi kampanye 3-step |
| `DonationHistory.php` | Riwayat donasi (pagination) dengan scroll event |
| ~~`AdminLogin.php`~~ | **SUDAH DIHAPUS** — login admin via AuthController |
| `AdminDashboard.php` | Dashboard admin + statistik |
| `Admin/CampaignManager.php` | CRUD kampanye |
| `Admin/DonationManager.php` | CRUD donasi umum |
| `Admin/CampaignDonationManager.php` | CRUD donasi kampanye |
| `Admin/UnitUsahaReportManager.php` | CRUD laporan unit usaha |

### Controllers (`app/Http/Controllers/Admin/`)
| File | Keterangan |
|---|---|
| `AuthController.php` | Login/logout admin (non-Livewire) |
| `DashboardController.php` | Dashboard wrapper views |
| `ExportController.php` | Export Excel donasi + campaign donasi + unit usaha |

### Views (`resources/views/`)
| Folder | Keterangan |
|---|---|
| `livewire/` | Livewire component views |
| `livewire/admin/` | Admin panel views |
| `admin/` | Non-Livewire admin views (login) |
| `components/layouts/` | Layouts: `app`, `admin`, `campaign`, `guest` |
| `components/` | Sub-components: `whatsapp-float`, dll |
| `emails/` | Template email notifikasi |

---

## ⚙️ Fitur-Fitur Penting

### Format Rupiah (`app/helpers.php`)
- `formatRupiah($amount)` → "Rp 1.000.000"
- `formatRupiahCompact($amount)` → "Rp 1.5Jt", "Rp 2.3M"

### Input Formatting (Nominal Rupiah)
- JavaScript untuk formatting otomatis (IDR locale) saat input
- `@input` → format pakai `Intl.NumberFormat('id-ID')`
- `@focus` → hapus titik untuk editing
- `@blur` → format ulang
- Di backend: `preg_replace('/[^0-9]/', '', $value)` untuk cleanup
- Dipakai di: form donasi umum, form donasi kampanye, form laporan unit usaha (create + edit)

### Validasi Per-Step
- Step 1: type, amount, phone, payment_method_id, donatur_name (jika tidak anonim)
- Step 2: proof_of_transfer (mimes: jpeg,jpg,png,pdf, max:2048)

### Penanganan Anonim
- Jika `is_anonymous = true`:
  - `donatur_name` tidak required di validasi
  - `donatur_name` disimpan sebagai "Hamba Allah"
  - Input nama donatur di-disable

### Notifikasi Email
- Donasi umum → `NewDonationMail` → `emails/new-donation.blade.php`
- Donasi kampanye → `CampaignDonationMail` → `emails/campaign-verification.blade.php`
- Dikirim ke: `config('mail.admin_address')` (env `MAIL_TO_ADMIN`, default `workrahmattb@gmail.com`)
- Menggunakan Queue (ShouldQueue interface) — `send()` otomatis di-queue karena Mailable implements ShouldQueue. **Pastikan queue worker jalan di production** (kalau tidak, email tidak terkirim senyap).
- Try-catch agar tidak gagalkan donasi jika email error

### Export Excel (`ExportController`)
- Donasi umum: 10 kolom (No, TRX ID, Nama, Telepon, Tipe, Bank, Jumlah, Anonim, Status, Tanggal)
- Donasi kampanye: 10 kolom (sama + Kampanye)
- Format: `.xlsx` (PhpSpreadsheet)
- Header styling: green background + white text
- Auto-size columns

### Campaign Progress Calculation
- `Campaign.php` memiliki accessor:
  - `total_approved_amount` → sum amount dari donations approved
  - `total_approved_donors` → count donations approved
  - `progress` → (total_approved_amount / target_amount) * 100, max 100%
- **FIXED**: Perhitungan progress 100% menggunakan `total_approved_amount` (computed accessor dari database). Kolom `raised_amount` dan `donor_count` sudah **tidak digunakan lagi** untuk menghindari double counting.

### Soft Delete / Hard Delete
- Semua delete adalah hard delete (force delete)
- Donasi: file bukti transfer ikut dihapus (via `deleting` boot event)
- Campaign: image ikut dihapus

---

## 🎨 Desain & Styling

### Tema Warna
- **Primary**: Emerald (#10b981) → Teal (#0d9488)
- **Wakaf Pembangunan**: Emerald
- **Wakaf Produktif**: Teal
- **Donasi Pendidikan**: Cyan
- **Unit Usaha**: Purple → Violet
- **Kampanye**: Blue → Indigo
- **Background**: Gradient emerald-50 → white → teal-50

### Pola View
- **Desktop**: Table view (hidden on mobile via `hidden md:block`)
- **Mobile**: Card view (hidden on desktop via `md:hidden`)
- **Modals**: Form create/edit di modal overlay
- **Flash Messages**: Fixed bottom-right toast dengan auto-hide (3 detik)
- **Semua admin view** menggunakan `#[Layout('components.layouts.admin')]`

### Layouts
- `components.layouts.app` — Layout default (public)
- `components.layouts.admin` — Layout admin (navbar + slot)
- `components.layouts.campaign` — Layout halaman kampanye (navbar + footer)
- `components.layouts.guest` — Layout login minimalis

---

## 🧪 Catatan & Edge Cases

### 🧹 Cleanup — Dead Code Dihapus
- ~~`CampaignDonationForm`~~ — **SUDAH DIHAPUS**
- ~~`CampaignDonationFlow`~~ — **SUDAH DIHAPUS**
- ~~`AdminLogin`~~ — **SUDAH DIHAPUS** (login via AuthController)
- ~~`CampaignController`~~ — **SUDAH DIHAPUS** (semua route pakai Livewire)
- ~~`campaign-success.blade.php`~~ — **SUDAH DIHAPUS** (hanya dipanggil CampaignController)
- ~~`admin-login.blade.php`~~ — **SUDAH DIHAPUS** (Livewire login tidak dipakai)
- ~~`admin/donations.blade.php`~~ — **SUDAH DIHAPUS** (digantikan dashboard-wrapper)

### ✅ Bug — Fixed
- ~~Double counting campaign~~ — **FIXED**: increment/decrement `raised_amount` dan `donor_count` di `CampaignDonationManager` dihapus. Perhitungan sepenuhnya via computed accessor.
- ~~Debug logging~~ — **FIXED**: Semua `\Log::info()` di `CampaignDonate.php` dihapus.
- ~~Halaman depan error~~ — **FIXED**: Variable statistik (`$wakafPembangunanTotal`, `$featuredCampaigns`, dll) dikirim dari `DonationFlow::render()` ke layout.
- ~~Edit unit usaha "tidak bisa"~~ — **FIXED**: Penyebabnya file `public/hot` **stale** (artefak `npm run dev` yang masih menunjuk ke Vite dev server `:5173` yang mati) → semua CSS/JS gagal load → UI tampil rusak sehingga seolah tidak bisa diedit. Solusi: hapus `public/hot` + `npm run build`. Backend edit sebenarnya selalu benar (dibuktikan test + browser).
- ~~Tambah Laporan unit usaha: submit pertama tidak tersimpan, baru tersimpan di submit kedua~~ — **FIXED**: Field `jumlah_uang` memakai `wire:model.blur` (sync via request terpisah) sementara field lain memakai `wire:model` deferred. Saat klik "Simpan Laporan", request blur sync dan request `create()` saling berebut (race condition Livewire yang dikenal — lihat github.com/livewire/livewire discussions #8571/#9858) → percobaan pertama `jumlah_uang` masih kosong saat validasi → gagal. **Solusi**: ganti `wire:model.blur="jumlah_uang"` → `wire:model="jumlah_uang"` (deferred) di form create & edit sehingga nilainya dibaca & dikirim **atomik bersama request submit**. Formatter Alpine (`@input`/`@blur`) tetap dipertahankan; cleanup tetap lewat `updatedJumlahUang()` + `preg_replace` di `create()`/`update()`. Regression test: `test_create_saves_report_on_first_submit_without_blur_sync`.
  - **⚠️ Pola serupa masih ada** di `donation-flow.blade.php` & `campaign-donate-form.blade.php` (field `amount` pakai `wire:model.blur` + tombol `wire:click="goToStep2"`). Berpotensi race yang sama — belum diubah karena di luar scope; pantau jika user lapor masalah donasi tidak tersimpan di submit pertama.
- ~~Toast sukses unit usaha menutupi tombol~~ — **FIXED**: Toast `fixed bottom-4 right-4 z-50` di unit-usaha tidak punya auto-dismiss dan bisa menutupi tombol Edit/Hapus → tambah `x-data` + `x-init="setTimeout(() => show = false, 3000)"` (konsisten dengan campaign-manager).
- ~~Pagination admin error 500 "Public method [next] not found"~~ — **FIXED**: View pagination `resources/views/vendor/livewire/tailwind.blade.php` memakai `wire:click="next"/"previous"` (nama custom yang hanya ada di `DonationHistory`) → diganti ke **method standar trait** `WithPagination`: `nextPage`/`previousPage`. Semua komponen admin (unit usaha, kelola donasi, kampanye) langsung ter-fix. `DonationHistory` sekarang override `nextPage`/`previousPage`/`gotoPage` via **trait alias** (`parent*`) supaya event `scrollToRiwayat` tetap di-dispatch. Properti redundan `#[Url(as: 'page')] public $page` di `UnitUsahaReportManager` dihapus (WithPagination sudah handle URL `?page=`).
  - **PENTING — konvensi**: Jangan pernah menambah method custom `next()`/`previous()` di komponen; selalu pakai nama standar trait (`nextPage`, `previousPage`, `gotoPage`). `CampaignList` aman karena tanpa `WithPagination` ia memakai pagination Laravel biasa (link href).

### 🧪 Testing
- `tests/Feature/UnitUsahaReportManagerTest.php` — regression test alur **create → edit → update** laporan unit usaha, termasuk input format ribuan (`1.500.000` tersimpan benar sebagai `1500000`).
- `tests/Feature/PaginationTest.php` — regression test pagination: view memakai nama method standar, nextPage/gotoPage unit usaha bekerja, dan DonationHistory tetap dispatch `scrollToRiwayat`.
  ```bash
  php artisan test --filter=UnitUsahaReportManagerTest
  php artisan test --filter=PaginationTest
  ```
- **Catatan**: `phpunit.xml` menyetel sqlite :memory:, tapi test praktis berjalan di **MySQL asli** (`lwsvidecode`). Karena itu test memakai `DatabaseTransactions` (rollback aman, jangan pernah pakai `RefreshDatabase` di sini!) dan data test dibuat agar selalu urut di atas (mis. tanggal `2030-01-01`).

### Keamanan
- Admin middleware hanya cek `is_admin` boolean
- Semua route admin pakai `->middleware('admin')`
- Tidak ada role-based access selain admin flag

### SPA Navigation
- `wire:navigate` digunakan di:
  - Dashboard → Kelola Kampanye
  - Dashboard → Laporan Unit Usaha
  - Dashboard → Kelola Donasi
  - Halaman Unit Usaha → Kembali ke Dashboard
- Link ke halaman publik (donasi) tetap pakai `target="_blank"`

---

## 📝 Migration Notes

Semua migration ada di `database/migrations/`:

| File | Tabel |
|---|---|
| `0001_01_01_000000_create_users_table.php` | users |
| `2026_02_18_000001_create_payment_methods_table.php` | payment_methods |
| `2026_02_18_000002_create_donations_table.php` | donations |
| `2026_02_19_013211_add_is_admin_to_users_table.php` | Add is_admin |
| `2026_02_19_050101_add_proof_of_transfer_to_donations_table.php` | Add proof_of_transfer |
| `2026_02_20_020856_add_phone_to_donations_table.php` | Add phone |
| `2026_02_28_034720_change_donation_types_enum.php` | Alter enum |
| `2026_03_01_071116_create_campaigns_table.php` | campaigns |
| `2026_03_01_071415_create_campaign_donations_table.php` | campaign_donations |
| `2026_03_05_024145_add_logo_to_payment_methods_table.php` | Add logo |
| `2026_06_05_000001_create_unit_usaha_reports_table.php` | unit_usaha_reports |

---

## 🚀 Cara Menjalankan

```bash
# Development (dari composer.json scripts)
composer dev

# Atau manual:
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0
npm run dev

# Build production
npm run build
```

> ⚠️ **PENTING — `public/hot` stale**: Jika Vite dev server dimatikan, pastikan file `public/hot` **tidak ada**. File stale ini membuat semua halaman load asset dari `http://[::1]:5173` yang mati → CSS/JS gagal → UI rusak (gejala: admin seolah "tidak bisa edit").
> - File ada di `.gitignore` (aman, tidak ter-commit)
> - Cek: `ls public/hot` — hapus jika ada saat dev server mati
> - Setelah `npm run build`, halaman memakai asset statis dari `/build/assets/`
