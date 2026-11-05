# Project Memory — Lembaga Wakaf Syafa'aturrasul (LWS)

Terakhir diperbarui: 5 November 2026 (Asia/Jakarta).

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
| `/admin/export/penyaluran` | `ExportController@exportDisbursements` | `admin.export.penyaluran` | Export Excel seluruh data penyaluran (khusus admin) |
| `/admin/penyaluran` | `DisbursementManager` | `admin.penyaluran` | CRUD penyaluran dana |
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
- `mount($slug = null)` — menerima route param `{slug}` langsung (Livewire meneruskan route params ke mount secara otomatis), dengan fallback `request()->route('slug')` untuk kompatibilitas saat diuji via Livewire component test.

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
- **4 Kartu Statistik**: Total Terkumpul Donasi Umum + Kampanye (approved), Total Unit Usaha, Penyaluran Total, SALDO LWS
- **Grafik Tren Donasi Perbulan** (12 bulan, bar chart Chart.js) — dipecah per **tipe donasi**: Wakaf Pembangunan, Wakaf Produktif, Donasi Pendidikan
- **Grafik Tren Donasi Pertahun** (2024 sampai tahun berjalan, bar chart Chart.js) — dipecah per tipe donasi
- **Donasi per Kategori** (progress bar: Wakaf Pembangunan, Produktif, Pendidikan + doughnut komposisi)
- **Total Donasi Terkumpul per Tahun** di atas Komposisi per Kategori: menjumlahkan donasi umum dan kampanye berstatus `approved`, berdasarkan `created_at`.
- Pengumpulan wakaf dimulai pada 2024; grafik tahunan otomatis menambahkan tahun berjalan, termasuk 2027 dan seterusnya. Tahun tanpa transaksi bernilai nol.
- **Donasi Terbaru** (tabel 5 data terakhir)
- **Grafik Penyaluran Donasi** tepat di bawah Donasi Terbaru:
  - Total Dana Disalurkan dan ringkasan nominal per kategori untuk semua periode.
  - Grafik batang **Penyaluran per Tahun** berdasarkan `disbursed_at`, sejak 2024 sampai tahun berjalan.
  - Tiga seri: Wakaf Pembangunan, Wakaf Produktif, Donasi Pendidikan.
  - Data tanpa tanggal masuk total keseluruhan, tetapi tidak masuk grafik tahunan; tampil pemberitahuan untuk melengkapi tanggal.
  - Grafik donat penyaluran sudah dihapus sesuai permintaan pengguna; grafik donat Komposisi per Kategori donasi tetap ada.
  - Tautan Kelola Penyaluran menggunakan `wire:navigate`.
- **Laporan Unit Usaha**:
  - 4 kartu statistik (total laporan, total uang, rata-rata, jumlah unit)
  - **Grafik Tren Pemasukan Perbulan** (bar chart, Chart.js) — sum `jumlah_uang` per bulan dari semua laporan
  - **Grafik Komposisi per Unit Usaha** (doughnut chart, Chart.js) — sum per `nama` unit usaha
  - 5 laporan terbaru
- **Quick Actions**: Kelola Penyaluran, Kelola Kampanye, Laporan Unit Usaha, Kelola Donasi, Lihat Halaman Donasi

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
- **⚠️ Konvensi — JANGAN pakai `wire:model.blur`**: Sudah 2× jadi sumber bug "submit pertama gagal, kedua berhasil" (unit usaha & form donasi). Field yang divalidasi pada saat klik tombol (submit/`goToStep2`) WAJIB memakai `wire:model` **deferred** agar nilainya dikirim atomik bersama request aksi. Formatter Alpine (`@input`/`@focus`/`@blur`) tetap boleh dipakai — itu hanya manipulasi tampilan DOM, bukan sync Livewire.

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
  - ~~Pola serupa di form donasi~~ — **FIXED**: Field `amount` di `donation-flow.blade.php` & `campaign-donate-form.blade.php` juga memakai `wire:model.blur` + `wire:click="goToStep2"` (pola race yang sama: request blur sync vs request goToStep2, nominal bisa tidak tersinkron di submit pertama). **Solusi**: ganti `wire:model.blur="amount"` → `wire:model="amount"` (deferred) di kedua view. Formatter Alpine (`@input`/`@focus`/`@blur`) tetap dipertahankan; cleanup via `updatedAmount()` + `preg_replace` tetap berjalan. Regression test: `tests/Feature/DonationFormNoBlurSyncTest.php` (markup memakai deferred + flow goToStep2 dari input `1.500.000` berhasil di percobaan pertama, nominal tersimpan `1500000`). Catatan: `CampaignDonate::mount()` kini menerima `$slug = null` (fallback `request()->route('slug')`) supaya bisa diuji via Livewire component test.
- ~~Toast sukses unit usaha menutupi tombol~~ — **FIXED**: Toast `fixed bottom-4 right-4 z-50` di unit-usaha tidak punya auto-dismiss dan bisa menutupi tombol Edit/Hapus → tambah `x-data` + `x-init="setTimeout(() => show = false, 3000)"` (konsisten dengan campaign-manager).
- ~~Tombol Batal di modal tidak bisa diklik~~ — **FIXED**: Akar masalahnya **layout, bukan logic** — konten modal (4 field + pesan error validasi) lebih tinggi dari `max-h-[95vh] sm:max-h-[90vh]`, sehingga baris tombol Batal/Simpan terdorong ke bawah **di luar area scroll modal** (`overflow-y-auto`). Terbukti di headless Chrome: di viewport pendek Batal di posisi y=560–608 padahal area modal hanya sampai y=487 → klik tidak mencapai tombol (terekam: campaign modal Batal di y=1041 vs viewport 900 → klik gagal). **Solusi**: baris tombol diubah jadi **sticky footer** — `sticky bottom-0 -mx-4 sm:-mx-6 -mb-4 sm:-mb-6 px-4 sm:px-6 py-4 bg-white border-t border-slate-200 rounded-b-2xl` — sehingga selalu terlihat & bisa diklik di dasar modal, bahkan saat konten di-scroll atau pesan validasi muncul. Diterapkan di 4 modal: create & edit unit usaha + create & edit campaign. Verifikasi: Batal kini `visible: true` di viewport 513px & modal tertutup (posisi y≈447).
- ~~Interaksi pertama di modal unit usaha gagal setelah logout → login → masuk via SPA~~ — **FIXED**: Akar masalah = **Livewire dimuat DUA KALI** (double Livewire + double Alpine). `resources/js/app.js` mengimpor `livewire.esm` (copy #1, via Vite) sementara semua layout memakai `@livewireScripts` yang juga memuat UMD `/livewire-acf0d1ab/livewire.js` (copy #2). Dua Alpine sama-sama start di `DOMContentLoaded` dan saling berebut inisialisasi DOM → **setelah SPA navigation** (`wire:navigate`), interaksi pertama pada komponen baru (wire:click Batal / wire:submit Simpan) gagal senyap, interaksi kedua berhasil (gejala "pertama tidak, kedua baru bisa"). Terbukti di console: `Detected multiple instances of Livewire running` + `multiple instances of Alpine running`, dan reproduksi headless Chrome: Batal#1 tidak menutup modal setelah SPA nav, tapi Batal#2 & Batal setelah hard reload berhasil. **Solusi** (konvensi resmi Livewire 4 + Vite):
  - `resources/js/app.js`: panggil `Livewire.start()` **di akhir file** — harus setelah definisi `window.init*Chart` (Alpine mengevaluasi `x-init` saat inisialisasi DOM, helper chart harus sudah ada).
  - **Semua layout** ganti `@livewireScripts` → `@livewireScriptConfig` (hanya set `window.livewireScriptConfig`, tidak memuat UMD kedua): `components/layouts/admin.blade.php`, `components/layouts/app.blade.php`, `components/layouts/guest.blade.php`, `components/layouts/campaign.blade.php`, `admin/login.blade.php`, `donation.blade.php`. `@livewireStyles` tetap dipertahankan.
  - Verifikasi CDP headless: Batal percobaan pertama setelah SPA nav kini menutup modal ✅, simpan laporan sukses ✅, **tanpa warning multiple instances** ✅, grafik dashboard (Chart.js) tetap render ✅, SPA tanpa reload ✅, halaman publik (donasi/kampanye) tetap normal ✅. 9 test PASS.
  - **⚠️ Konvensi**: JANGAN menaruh `@livewireScripts` di layout yang juga memuat `app.js` via Vite (app.js sudah memuat Livewire ESM). Selalu pakai `@livewireScriptConfig`. Jika ada layout baru, pakai pola yang sama.
- ~~Pagination admin error 500 "Public method [next] not found"~~ — **FIXED**: View pagination `resources/views/vendor/livewire/tailwind.blade.php` memakai `wire:click="next"/"previous"` (nama custom yang hanya ada di `DonationHistory`) → diganti ke **method standar trait** `WithPagination`: `nextPage`/`previousPage`. Semua komponen admin (unit usaha, kelola donasi, kampanye) langsung ter-fix. `DonationHistory` sekarang override `nextPage`/`previousPage`/`gotoPage` via **trait alias** (`parent*`) supaya event `scrollToRiwayat` tetap di-dispatch. Properti redundan `#[Url(as: 'page')] public $page` di `UnitUsahaReportManager` dihapus (WithPagination sudah handle URL `?page=`).
  - **PENTING — konvensi**: Jangan pernah menambah method custom `next()`/`previous()` di komponen; selalu pakai nama standar trait (`nextPage`, `previousPage`, `gotoPage`). `CampaignList` aman karena tanpa `WithPagination` ia memakai pagination Laravel biasa (link href).

### 🧪 Testing
- `tests/Feature/UnitUsahaReportManagerTest.php` — regression test alur **create → edit → update** laporan unit usaha, termasuk input format ribuan (`1.500.000` tersimpan benar sebagai `1500000`).
- `tests/Feature/PaginationTest.php` — regression test pagination: view memakai nama method standar, nextPage/gotoPage unit usaha bekerja, dan DonationHistory tetap dispatch `scrollToRiwayat`.
- `tests/Feature/AdminSpaNavigationTest.php` — regression test: semua tombol "Kembali ke Dashboard" (kelola kampanye, kelola donasi umum, kelola donasi kampanye) harus memakai `wire:navigate` (SPA) — mencegah siapa pun menghapusnya di masa depan.
- `tests/Feature/LivewireSingleLoadTest.php` — regression test: semua layout memakai `@livewireScriptConfig` (bukan `@livewireScripts`) + memuat `app.js` via Vite, dan `app.js` memanggil `Livewire.start()` — mencegah double Livewire/Alpine yang bikin interaksi pertama gagal setelah SPA navigate.
- `tests/Feature/DonationFormNoBlurSyncTest.php` — regression test: field `amount` di form donasi umum & donasi kampanye memakai `wire:model` deferred (bukan `wire:model.blur`) + flow `goToStep2` lolos di percobaan pertama dengan input format ribuan (`1.500.000` → `1500000`).
  ```bash
  php artisan test --filter=UnitUsahaReportManagerTest
  php artisan test --filter=PaginationTest
  php artisan test --filter=DonationFormNoBlurSyncTest
  ```
- **Catatan pengujian**: `phpunit.xml` menyetel SQLite `:memory:`. Test lama memakai `DatabaseTransactions`; catatan sebelumnya menyebut koneksi MySQL pada lingkungan lain. Pastikan koneksi efektif sebelum menjalankan pengujian yang menyentuh schema. Test baru `DisbursementManagerTest` dan `AdminDashboardYearlyDonationsTest` telah berhasil dijalankan dengan SQLite sementara. Migration historis `change_donation_types_enum` menggunakan SQL khusus MySQL dan dilewati oleh setup test dashboard tersebut.

### Keamanan
- Admin middleware hanya cek `is_admin` boolean
- Semua route admin pakai `->middleware('admin')`
- Tidak ada role-based access selain admin flag

### SPA Navigation
- `wire:navigate` digunakan di **semua navigasi internal admin** (tanpa full page reload):
  - Dashboard → Kelola Kampanye / Laporan Unit Usaha / Kelola Donasi (quick actions)
  - Dashboard → Lihat Semua Laporan (unit usaha)
  - **Kembali ke Dashboard** di semua halaman kelola: unit usaha, kelola kampanye, kelola donasi umum, kelola donasi kampanye
  - Link nav campaign-manager → Kelola Donasi Kampanye
  - Link footer dashboard → Kembali ke Halaman Donasi
- **Konvensi**: semua link navigasi internal (admin→admin, admin→publik) pakai `wire:navigate`. Hanya link yang memang harus buka tab baru yang pakai `target="_blank"` (quick action "Lihat Halaman Donasi") dan link download/export (tanpa wire:navigate).
- Logout tetap form POST biasa (bukan SPA).

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


## Pembaruan 4 November 2026 — Boost dan Penyaluran

### Laravel Boost

- Versi terpasang saat diperiksa: Laravel 12.52.0, Livewire 4.1.4, Laravel Boost 2.10.4.
- `.mcp.json` menghubungkan MCP Laravel Boost melalui `php artisan boost:mcp`.
- `boost.json` mengaktifkan MCP, guidelines, dan skill project.
- Tools Boost berhasil digunakan untuk schema database, koneksi database, dokumentasi, serta resolusi URL.
- Schema MySQL berhasil terbaca pada pekerjaan Penyaluran; hasil kosong pada pemeriksaan awal bukan kesimpulan bahwa database selalu kosong.

### Kelola Penyaluran

- Route: `/admin/penyaluran`, nama `admin.penyaluran`, middleware `admin`.
- Komponen: `app/Livewire/Admin/DisbursementManager.php`.
- View: `resources/views/livewire/admin/disbursement-manager.blade.php`.
- Model: `app/Models/Disbursement.php`.
- CRUD: tambah/edit melalui modal, daftar dengan pagination 15 data, tanggal penyaluran terbaru di atas (tanggal kosong paling bawah, ID terbaru sebagai pengurut tanggal yang sama), hapus dengan konfirmasi, notifikasi sukses, dan total dana disalurkan.
- Komponen memeriksa `is_admin` pada `boot()`; ID data edit memakai atribut `#[Locked]`.
- Data penyaluran dicatat terpisah; CRUD ini tidak mengurangi nilai donasi yang terkumpul.

| Kolom tabel `disbursements` | Tipe | Catatan |
|---|---|---|
| `id` | bigint unsigned, primary key | Auto increment |
| `description` | text | Wajib, maksimal 5000 karakter di formulir |
| `amount` | decimal(15,2) | Lebih dari nol, maksimal 9999999999999.99 |
| `type` | enum | `wakaf_pembangunan`, `wakaf_produktif`, `donasi_pendidikan` |
| `disbursed_at` | date nullable | Wajib pada tambah/edit; nullable untuk data lama |
| `created_at`, `updated_at` | timestamp nullable | Waktu pencatatan/perubahan |

- Form tanggal baru terisi tanggal hari ini; daftar menampilkan `d/m/Y`. Tanggal data lama yang kosong tampil sebagai “Belum diisi”.
- Nominal menggunakan titik ribuan otomatis saat diketik: `1500000` menjadi `1.500.000`. Mendukung koma desimal, misalnya `2.500.000,50`; backend menormalisasi menjadi angka sebelum validasi dan penyimpanan.
- Factory: `database/factories/DisbursementFactory.php`.
- Seeder opsional: `database/seeders/DisbursementSeeder.php` membuat lima data contoh; tidak dijalankan otomatis dalam pekerjaan ini.
- Migration berikut sudah diterapkan ke database lokal:
  - `2026_11_04_140123_create_disbursements_table.php`
  - `2026_11_04_140501_add_disbursed_at_to_disbursements_table.php`

### File dashboard dan pengujian terkait

- Perhitungan grafik: `app/Livewire/AdminDashboard.php`.
- Posisi/tampilan grafik: `resources/views/livewire/admin/dashboard.blade.php`.
- Chart.js: `resources/js/app.js`; grafik penyaluran tahunan memakai helper `initYearlyChart` dengan tiga seri kategori. `initTotalYearlyChart` mendukung label dataset opsional.
- `tests/Feature/DisbursementManagerTest.php`: CRUD, akses admin, validasi kategori/jumlah/tanggal, nominal ribuan dan desimal, serta daftar data.
- `tests/Feature/AdminDashboardYearlyDonationsTest.php`: total donasi approved, tahun kosong, total penyaluran per kategori, tahun berdasarkan tanggal penyaluran, data tanpa tanggal, dan penambahan tahun 2027.
- Verifikasi terakhir per bagian: lima test CRUD lulus setelah format nominal diperbarui; tujuh test dashboard lulus setelah pemisahan kategori dan penghapusan donat.
- Build Vite berhasil pada pekerjaan grafik. Perubahan terakhir hanya Blade/perhitungan PHP; helper JavaScript untuk grafik tiga kategori sudah tersedia pada build.


### Ekspor Excel Penyaluran

- Tombol **Export Excel** tersedia di header Kelola Penyaluran.
- Endpoint GET `/admin/export/penyaluran`, route `admin.export.penyaluran`, dilindungi middleware `admin`.
- Implementasi mengikuti `ExportController` dan PhpSpreadsheet 5.5.0 yang sudah terpasang.
- File `penyaluran-YYYY-MM-DD-HHmmss.xlsx` berisi seluruh data (bukan hanya halaman pagination aktif), urutan ID terbaru.
- Kolom: No, Tanggal Penyaluran, Deskripsi Penyaluran, Jenis Donasi, Jumlah Disalurkan (Rp).
- Tanggal kosong ditampilkan “Belum diisi”; nominal berupa angka dengan dua desimal agar dapat dihitung di Excel. Deskripsi disimpan sebagai teks eksplisit agar tidak dieksekusi sebagai formula.
- Link download tidak menggunakan `wire:navigate`. Tidak ada migration atau dependency baru.
- Setelah penambahan ekspor, `DisbursementManagerTest` lulus: 8 test, 53 assertion, mencakup isi XLSX, seluruh data lintas pagination, data kosong, dan akses admin.


### Kartu keuangan dashboard admin

- Urutan kartu teratas: **Total Terkumpul Donasi Umum + Kampanye**, **Total Unit Usaha**, **Penyaluran Total**, **SALDO LWS**. Semua menampilkan nominal Rupiah, bukan jumlah transaksi.
- Donasi terkumpul hanya menjumlahkan `Donation.amount` dan `CampaignDonation.amount` berstatus `approved`; pending/rejected tidak masuk saldo.
- Unit usaha menjumlahkan seluruh `UnitUsahaReport.jumlah_uang`, bukan hanya SR Bakery.
- Penyaluran menjumlahkan seluruh `Disbursement.amount`, termasuk data tanpa tanggal.
- Rumus: `SALDO LWS = donasi umum approved + kampanye approved + pemasukan unit usaha - total penyaluran`. Saldo negatif tetap ditampilkan apa adanya.
- Data view baru: `totalCollectedDonations` dan `lwsBalance` pada `AdminDashboard`.
- Pengujian dashboard terakhir: 9 test, 27 assertion lulus. Build Vite berhasil. Tidak ada migration baru untuk perubahan kartu.


### Penamaan tampilan: Kampanye menjadi Donasi

- Semua label pengguna yang sebelumnya menyebut “Kampanye” diganti menjadi “Donasi”: halaman utama, daftar/detail/form publik, pengelolaan admin, dashboard, pesan sukses/error, email, dan header/nama file ekspor.
- Frasa “Donasi Kampanye” disederhanakan menjadi “Donasi”, bukan “Donasi Donasi”. “Donasi Umum” tetap digunakan untuk membedakan sumber penerimaan.
- Nama internal `Campaign`, `CampaignDonation`, komponen, tabel database, route `campaigns.*`, dan URL `/kampanye` dipertahankan untuk kompatibilitas. Tidak ada migration atau perubahan data tersimpan.
- Penyebutan kampanye pada bagian historis dokumentasi mengacu pada fitur yang sekarang berlabel Donasi.


### Grafik unit usaha per tahun

- Dashboard bagian Laporan Unit Usaha menampilkan **Pemasukan Unit Usaha per Tahun**, setelah grafik bulanan/komposisi dan sebelum tabel laporan terbaru.
- Menjumlahkan `jumlah_uang` seluruh unit usaha berdasarkan tahun `tanggal` laporan, bukan `created_at`.
- Rentang 2024 sampai tahun berjalan, otomatis bertambah; tahun kosong bernilai nol.
- Berbeda dengan grafik bulanan yang tetap khusus Hasil Keuntungan SR Bakery.
- Data view: `unitUsahaYearlyAmounts`; Chart.js memakai `initTotalYearlyChart` dengan label pemasukan unit usaha dan warna ungu opsional.
- Tidak ada migration baru.


### Urutan tabel Kelola Penyaluran

- Tabel memiliki kolom No; nomor berlanjut antarhalaman menggunakan `firstItem() + index`.
- Urutan berdasarkan `disbursed_at` terbaru, bukan waktu pencatatan; tanggal kosong di bawah, tanggal sama diurutkan ID menurun.
- Perubahan ini khusus daftar Kelola Penyaluran; urutan ekspor tetap seperti implementasi sebelumnya.
- Susunan dashboard terbaru: Donasi Terbaru → Laporan Unit Usaha → Penyaluran.


### Pencarian Kelola Penyaluran

- Kolom pencarian di atas tabel memfilter berdasarkan sebagian Deskripsi Penyaluran, tanpa membedakan huruf besar/kecil.
- `wire:model.live.debounce.300ms="search"`; perubahan pencarian mengembalikan pagination ke halaman pertama.
- Urutan tanggal terbaru, penempatan tanggal kosong di bawah, dan nomor berlanjut antarhalaman tetap berlaku pada hasil pencarian.
- Pencarian kosong menampilkan semua data; hasil kosong memiliki pesan khusus.
- Total Dana Disalurkan dan Export Excel tetap mencakup seluruh data, tidak terfilter pencarian.


### 5 November 2026 — Pencarian Laporan Unit Usaha

- Kelola Laporan Unit Usaha memiliki pencarian berdasarkan sebagian `nama` laporan/unit usaha atau `catatan`, tanpa membedakan huruf besar/kecil; spasi awal/akhir diabaikan.
- Input di atas daftar memakai `wire:model.live.debounce.300ms="search"`; perubahan pencarian mengembalikan pagination ke halaman pertama.
- Hasil tetap diurutkan tanggal terbaru, lalu ID terbaru. Tampilan desktop dan mobile memiliki pesan khusus saat hasil pencarian kosong.
- Ringkasan total/rata-rata dan Export Excel tetap mencakup seluruh data.
- `tests/Feature/UnitUsahaReportSearchTest.php`: dua test lulus (5 assertion), menguji nama/catatan, urutan tanggal, pengosongan pencarian, total tetap utuh, dan reset pagination. Build Vite berhasil; tidak ada migration baru.


### 5 November 2026 — Dashboard dan tabel laporan publik

- Halaman `/laporan` (route `reports.dashboard`, komponen `PublicDashboard`) dapat diakses tanpa login, dengan empat kartu keuangan dan semua jenis grafik dashboard: donasi bulanan/tahunan, total donasi tahunan, komposisi kategori, pemasukan SR Bakery bulanan, komposisi unit usaha, pemasukan unit usaha tahunan, dan penyaluran per tahun.
- Tombol **Lihat Dashboard Laporan LWS** ditambahkan ke beranda. Dashboard publik memiliki tombol tabel Penyaluran, Unit Usaha, dan Donasi Umum.
- Route `reports.table`: `/laporan/data/{section}`, hanya menerima `penyaluran`, `unit-usaha`, `donasi-umum`. Komponen `PublicReportTable` memiliki section terkunci, pencarian, dan pagination 15 data. Tidak menyediakan method CRUD atau akses ekspor admin.
- Tabel publik penyaluran: nomor, tanggal, deskripsi, kategori, nominal; tanggal terbaru di atas, tanggal kosong terakhir.
- Tabel publik unit usaha: nomor, tanggal, nama unit/laporan, nominal, catatan; tanggal terbaru di atas.
- Tabel publik donasi umum hanya menampilkan donasi `approved`: nomor, tanggal, kategori, nominal. Seluruh identitas donatur disembunyikan (termasuk nama, nomor telepon, kode transaksi, rekening dan bukti transfer); query hanya memilih kolom yang ditampilkan.
- Perhitungan dashboard dipindahkan ke `app/DashboardReportData.php` agar dipakai bersama admin/publik. `AdminDashboard` mempertahankan data dan view sebelumnya. Mode publik memakai kategori donasi approved saja agar informasi publik sesuai dana terverifikasi.
- File UI: `resources/views/livewire/public-dashboard.blade.php` dan `public-report-table.blade.php`, memakai layout publik yang sudah ada. Tidak mengarahkan pengunjung ke route admin.
- `PublicReportsTest` menguji akses guest, semua grafik, data pribadi tersembunyi, status approved, pencarian/pagination, route invalid, dan admin tetap terlindungi.
- Verifikasi: 29 test lulus (138 assertion) pada PublicReportsTest, AdminDashboardYearlyDonationsTest, DisbursementManagerTest, UnitUsahaReportSearchTest; build Vite berhasil. Tidak ada migration/dependency baru.


### Penamaan frontend publik: Donasi Umum menjadi Wakaf

- Label “Donasi Umum” di frontend publik diganti menjadi “Wakaf”: tombol/navigasi laporan, judul tabel “Laporan Wakaf”, dan ringkasan dashboard publik “Total Terkumpul Wakaf + Donasi”.
- Label pada admin tetap Donasi Umum. Route `reports.table`, section/URL `donasi-umum`, model dan tabel internal tidak berubah.


### Nama donatur pada tabel Wakaf publik

- Tabel Laporan Wakaf sekarang memiliki kolom Nama Donatur atas permintaan pengguna.
- Nama donatur non-anonim ditampilkan; donatur dengan `is_anonymous` ditampilkan sebagai **Hamba Allah**.
- Nama anonim disamarkan langsung dalam proyeksi query menggunakan CASE; nama aslinya tidak ikut diambil untuk tabel publik.
- Nomor telepon, bukti transfer, dan kode transaksi tetap tidak ditampilkan. Hanya wakaf approved yang masuk tabel.
- Catatan ini menggantikan ketentuan sebelumnya bahwa semua nama donatur disembunyikan.


### Label tombol dashboard publik

- Kata “Tabel” dihapus dari tombol dashboard publik: **Lihat Penyaluran**, **Lihat Unit Usaha**, dan **Lihat Wakaf**. Tujuan route tetap sama.

- Teks pengantar “Informasi publik · Hanya untuk dilihat” dan keterangan wakaf disetujui/donatur anonim dihapus dari UI laporan publik. Aturan data approved dan penyamaran anonim tetap berlaku.
