# 🔧 FIX: Duplicate Entry Error pada PR Number

## ❌ ERROR YANG TERJADI

```
SQLSTATE[23000]: Integrity constraint violation: 1062 
Duplicate entry 'PR-0003/11/2025' for key 'purchase_requests_pr_number_unique'
```

**Artinya:** Nomor PR `PR-0003/11/2025` sudah ada di database, tidak boleh ada duplikat.

---

## ✅ SOLUSI (3 PILIHAN)

### **SOLUSI 1: Reset Database (Paling Mudah)** ⭐ RECOMMENDED

Jika Anda tidak perlu data lama, reset database sekaligus hapus semua data duplikat:

```powershell
php artisan migrate:fresh
```

**Penjelasan:**
- Menghapus semua data di database
- Recreate semua tabel dengan status kosong
- Tidak ada lagi duplikat data
- Anda bisa mulai buat PR baru dari awal

**Output yang benar:**
```
INFO  Preparing database.  
INFO  Running migrations.  
0001_01_01_000000_create_users_table ..................... DONE
0001_01_01_000001_create_cache_table ..................... DONE
... (semua migrations)
INFO  Successfully reset database.
```

---

### **SOLUSI 2: Hapus Duplikat Data Saja (Jika Ada Data Penting)**

Jika ingin keep data lain, hapus hanya PR duplikat:

#### **Via MySQL Command Line:**

```powershell
# Buka MySQL
mysql -u root -p

# Di MySQL prompt:
USE laravelpos;

# Lihat data PR yang ada
SELECT id, pr_number FROM purchase_requests ORDER BY id DESC;

# Hapus PR duplikat (ganti ID sesuai yang ingin dihapus)
DELETE FROM purchase_requests WHERE pr_number = 'PR-0003/11/2025' AND id != 1;

# Atau hapus semuanya:
DELETE FROM purchase_requests;
DELETE FROM purchase_request_items;

# Verify
SELECT * FROM purchase_requests;

# Exit
EXIT;
```

#### **Via PHPMyAdmin (GUI):**

1. Buka: `http://localhost/phpmyadmin`
2. Login
3. Database `laravelpos` → Tabel `purchase_requests`
4. Lihat data, klik pada PR yang duplikat
5. Klik **Delete** (icon tempat sampah)
6. Confirm

---

### **SOLUSI 3: Truncate Table (Kosongkan Semua Data)**

Hapus semua data di tabel purchase_requests:

#### **Via Command Line:**

```powershell
mysql -u root -p -e "USE laravelpos; TRUNCATE TABLE purchase_requests; TRUNCATE TABLE purchase_request_items;"
```

#### **Via Tinker (Laravel CLI):**

```powershell
php artisan tinker

# Di tinker prompt:
DB::table('purchase_requests')->truncate();
DB::table('purchase_request_items')->truncate();

# Exit
exit
```

---

## 🎯 STEP-BY-STEP SOLUTION (PILIH SATU)

### **JIKA INGIN RESET SEMUA (Recommended jika data tidak penting):**

```powershell
# Buka PowerShell di folder project
cd D:\TA_25\TA_ALIF_TGL28-main

# Reset database
php artisan migrate:fresh

# Lihat progress
# ✓ Semua table di-drop
# ✓ Semua table di-recreate kosong
# ✓ Database siap digunakan

# Test: buat PR baru
# Harus berhasil tanpa error duplicate
```

---

### **JIKA INGIN HAPUS DATA DUPLIKAT SAJA:**

```powershell
# Option A: Hapus hanya PR duplikat
mysql -u root -p laravelpos -e "DELETE FROM purchase_requests WHERE pr_number = 'PR-0003/11/2025' LIMIT 1;"

# Option B: Hapus semua PR data
mysql -u root -p laravelpos -e "TRUNCATE TABLE purchase_requests; TRUNCATE TABLE purchase_request_items;"

# Test: buat PR baru
# Harus berhasil
```

---

## 📊 PERBANDINGAN METODE

| Metode | Data Lama | PR Duplikat | Waktu | Kesulitan |
|--------|-----------|-------------|-------|-----------|
| Reset Database | ❌ Hilang | ✅ Fix | 1 menit | Sangat Mudah |
| Hapus Duplikat | ✅ Tetap | ✅ Fix | 2 menit | Mudah |
| Truncate Table | ❌ Hilang (PR saja) | ✅ Fix | 1 menit | Mudah |

---

## 🔍 DIAGNOSIS: PENYEBAB DUPLIKAT

Duplikat bisa terjadi dari:

1. **Reload/Refresh Page** - Form PR di-submit 2x tanpa sengaja
2. **Network Error** - Request terkirim 2x ke server
3. **Manual Insert** - Data di-insert langsung ke database
4. **Migration Issue** - Seeder insert data sama beberapa kali

**Solusi jangka panjang:**
- Tambahkan `unique constraint` pada PR number ✅ (sudah ada)
- Validasi di aplikasi (cek duplikat sebelum save)
- Peringatan user jangan reload form

---

## ✅ CARA MENCEGAH DUPLIKAT DI MASA DEPAN

### **1. Cek Duplikat Sebelum Save**

Di Controller (ProcurementController.php):

```php
public function storePurchaseRequest(Request $request)
{
    // Validasi
    $validated = $request->validate([
        'pr_number' => 'required|unique:purchase_requests',
        // ... field lain
    ]);

    // Cek apakah PR number sudah ada
    if (PurchaseRequest::where('pr_number', $validated['pr_number'])->exists()) {
        return back()->withErrors(['pr_number' => 'PR number sudah ada!']);
    }

    // Save
    PurchaseRequest::create($validated);
}
```

### **2. Disable Button Setelah Submit**

Di Blade View:

```html
<form id="prForm">
    <!-- form fields -->
    <button type="submit" id="submitBtn">Simpan PR</button>
</form>

<script>
document.getElementById('prForm').addEventListener('submit', function() {
    // Disable button agar tidak double-submit
    document.getElementById('submitBtn').disabled = true;
});
</script>
```

### **3. Peringatan User**

```html
<div class="alert alert-warning">
    <strong>⚠️ Perhatian:</strong> Jangan reload halaman setelah klik Simpan!
    Tunggu sampai redirect otomatis.
</div>
```

---

## 🚀 RECOMMENDED SOLUTION

**Jalankan ini sekarang:**

```powershell
# 1. Reset database (paling cepat & efektif)
php artisan migrate:fresh

# 2. Verify berhasil
php artisan migrate:status

# 3. Test: Buat PR baru di aplikasi
# Harusnya berhasil tanpa error duplicate

# 4. (Optional) Jalankan server
php artisan serve

# 5. Akses aplikasi: http://127.0.0.1:8000
```

---

## 📋 CHECKLIST

- [ ] Backup data lama (jika ada yang penting)
- [ ] Buka PowerShell di folder project
- [ ] Jalankan: `php artisan migrate:fresh`
- [ ] Tunggu sampai selesai (lihat "DONE")
- [ ] Verify: `php artisan migrate:status`
- [ ] Test: Buat PR baru di aplikasi
- [ ] ✅ Harusnya berhasil tanpa error!

---

## 🆘 JIKA MASIH ERROR

### **Error: "SQLSTATE[HY000]: General error: 1025"**

```powershell
# Jalankan dengan force
php artisan migrate:fresh --force
```

### **Error: "Database connection failed"**

```powershell
# Check .env
notepad .env

# Pastikan:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=laravelpos
# DB_USERNAME=root
# DB_PASSWORD=(password Anda)

# Test koneksi
php artisan tinker
DB::connection()->getPdo();
# Harus return object tanpa error
exit
```

### **MySQL tidak running**

```powershell
# Windows - Start MySQL service
Start-Service MySQL80

# Atau via GUI:
# Win + R → services.msc → cari MySQL80 → Start
```

---

## 📝 CATATAN

1. **Reset database = hapus semua data** - Backup dulu jika penting
2. **PR number unique** - Tidak boleh ada duplikat
3. **Jangan reload form** - Setelah submit, tunggu redirect
4. **Character set: utf8mb4** - Untuk support emoji

---

**YA, RESET DATABASE AKAN MEMPERBAIKI ERROR INI!** ✅

Duplikat data akan hilang dan database kembali kosong siap pakai baru.

