# Panduan Reset Password

## 📋 Ringkasan Masalah & Solusi

### Masalah Awal
1. **Email tidak terkirim** karena notifikasi menggunakan `ShouldQueue` tetapi queue worker tidak berjalan
2. **Hanya customer yang bisa reset password** - Admin/owner tidak bisa reset karena ada validasi role
3. **Konfigurasi SMTP sudah benar** di `.env` tapi tidak dieksekusi karena tertangguh di queue

### Solusi yang Diterapkan
✅ **Menghapus `ShouldQueue` dari ResetPasswordNotification** - Email langsung dikirim (synchronous)
✅ **Menghapus validasi role 'customer'** - Semua user bisa reset password
✅ **Email akan langsung dikirim** saat form submitted

---

## 🧪 Cara Testing

### 1. Pastikan Admin User Ada
```bash
php artisan migrate:fresh --seed
```

Admin credentials:
- Email: `admin@example.com`
- Password: `4dm1n@123`

### 2. Test Reset Password untuk Admin
- Buka `/forgot-password`
- Masukkan email: `admin@example.com`
- Klik "Kirim Link Reset Password"
- **Cek Gmail** untuk email dari `mhdalif1255@gmail.com`
- Email harusnya terima dalam beberapa detik (tidak tertangguh)

### 3. Verifikasi di Log
Email akan langsung terkirim jika tidak ada error di file logs:
```bash
tail -f storage/logs/laravel.log
```

---

## ⚙️ Konfigurasi SMTP Gmail (.env)

Konfigurasi sudah benar:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=mhdalif1255@gmail.com
MAIL_PASSWORD=knxytnrmdbkgjpdm
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=mhdalif1255@gmail.com
MAIL_FROM_NAME=Laravel
```

✅ Menggunakan **App Password Gmail** (bukan plain password)
✅ Port 587 dengan TLS encryption
✅ Semua konfigurasi sudah setup dengan benar

---

## 📝 File yang Diubah

### 1. `app/Notifications/ResetPasswordNotification.php`
**Sebelum:**
```php
class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;
```

**Sesudah:**
```php
class ResetPasswordNotification extends ResetPassword
{
    // Tanpa ShouldQueue - email langsung dikirim
```

**Alasan:** Menghilangkan queue agar email terkirim synchronously (langsung)

---

### 2. `app/Http/Controllers/Auth/PasswordResetLinkController.php`
**Sebelum:**
```php
$user = User::where('email', $request->email)
            ->where('role', 'customer')  // ❌ Hanya customer
            ->first();
```

**Sesudah:**
```php
$user = User::where('email', $request->email)  // ✅ Semua role
            ->first();
```

**Alasan:** Semua user (admin, owner, supervisor, customer) bisa reset password

---

## 🔧 Alternatif: Jika Ingin Tetap Menggunakan Queue

Jika ingin tetap menggunakan queue (async), jalankan worker di terminal lain:

```bash
# Terminal 1: Start queue worker
php artisan queue:work

# Terminal 2: Continue dengan aplikasi normal
php artisan serve
```

Queue worker akan memproses email di background. Namun, untuk development lebih sederhana menggunakan synchronous (solusi yang diterapkan).

---

## 🚀 Testing Flow

1. **Fresh Setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

2. **Akses Reset Password**
   - URL: `http://localhost:8000/forgot-password`

3. **Submit Email**
   - Input: `admin@example.com`

4. **Cek Email**
   - Buka Gmail
   - Filter dari `mhdalif1255@gmail.com`
   - Email harus terima dalam 5-10 detik

5. **Klik Link & Reset**
   - Klik link di email
   - Masukkan password baru
   - Submit

---

## 💡 Tips Troubleshooting

### Email tidak terkirim?
1. Cek `.env` - pastikan MAIL_* config sudah benar
2. Cek logs: `tail storage/logs/laravel.log`
3. Jika pakai Gmail 2FA, gunakan **App Password** (sudah diterapkan)

### Queue masih error?
```bash
# Clear queue cache
php artisan queue:flush

# Reset queue jika ada masalah
php artisan queue:restart
```

### SMTP Connection Error?
```bash
# Test SMTP connection
php artisan tinker
>>> Mail::raw('test', function($message) { $message->to('admin@example.com'); });
```

---

## 📧 Email Template

Template email berada di: `resources/views/emails/reset-password.blade.php`

Sudah include:
- ✅ Header dengan logo
- ✅ Greeting dengan nama user
- ✅ Button reset password
- ✅ Fallback link jika button tidak bekerja
- ✅ Warning keamanan (link berlaku 60 menit)
- ✅ Footer dengan info sekolah

---

## ✨ Status: SELESAI

✅ Email akan langsung terkirim saat user submit form reset password
✅ Admin dan semua role bisa reset password
✅ Notifikasi tidak tertangguh di queue lagi
