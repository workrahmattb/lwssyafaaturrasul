# 📧 Panduan Setting SMTP Gmail untuk Email Konfirmasi

## Langkah 1: Aktifkan 2-Factor Authentication (2FA)

1. Buka [Google Account](https://myaccount.google.com/)
2. Pilih **Security** di menu kiri
3. Di bagian "How you sign in to Google", aktifkan **2-Step Verification**
4. Ikuti langkah-langkah untuk mengaktifkan 2FA

## Langkah 2: Buat App Password

1. Setelah 2FA aktif, buka [App Passwords](https://myaccount.google.com/apppasswords)
2. Jika diminta, login ulang dan verifikasi 2FA
3. Di bagian "App", pilih **Mail**
4. Di bagian "Device", pilih **Other (Custom name)**
5. Beri nama: `LWS Videcode` atau nama aplikasi Anda
6. Klik **Generate**
7. Google akan menampilkan **16 karakter password** (contoh: `abcd efgh ijkl mnop`)
8. **Copy password ini** (tanpa spasi)

## Langkah 3: Update File .env

Edit file `.env` dan ganti konfigurasi berikut:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com        # Ganti dengan email Gmail Anda
MAIL_PASSWORD=abcdefghijklmnop           # Paste App Password (16 karakter, tanpa spasi)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@lwsvidecode.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### ⚠️ Penting:
- `MAIL_USERNAME`: Gunakan email Gmail lengkap (contoh: `workrahmattb@gmail.com`)
- `MAIL_PASSWORD`: Gunakan **App Password** (BUKAN password Gmail biasa)
- App Password terdiri dari 16 karakter, hapus spasi jika ada

## Langkah 4: Clear Config Cache

Jalankan command berikut di terminal:

```bash
php artisan config:clear
php artisan cache:clear
```

## Langkah 5: Test Email

Untuk memastikan konfigurasi sudah benar, Anda bisa test dengan command:

```bash
php artisan tinker
```

Kemudian jalankan:

```php
Mail::raw('Test email dari LWS Videcode', function($message) {
    $message->to('your-email@gmail.com')
            ->subject('Test Email');
});
```

Jika berhasil, email akan terkirim tanpa error.

## 🔧 Troubleshooting

### Error: "Connection could not be established with host smtp.gmail.com"
- Pastikan port 587 tidak diblokir firewall
- Coba gunakan port 465 dengan `MAIL_ENCRYPTION=ssl`

### Error: "Authentication failed"
- Pastikan menggunakan **App Password**, bukan password Gmail biasa
- Cek apakah 2FA sudah diaktifkan
- Pastikan tidak ada spasi di App Password

### Error: "Sender address not accepted"
- Pastikan `MAIL_FROM_ADDRESS` menggunakan domain yang sama dengan `MAIL_USERNAME`
- Atau gunakan email Gmail yang sama untuk keduanya

## 📝 Alternatif Port SMTP Gmail

| Port | Encryption | Keterangan |
|------|------------|------------|
| 587  | tls        | **Recommended** (default) |
| 465  | ssl        | Alternative jika 587 bermasalah |

## 🎯 Email yang Dikirim

Setelah konfigurasi ini, sistem akan mengirim email untuk:
- ✅ Konfirmasi donasi baru ke admin
- ✅ Notifikasi pembayaran
- ✅ Email verifikasi (jika ada)

---

**Last Updated:** {{ date('Y-m-d') }}
