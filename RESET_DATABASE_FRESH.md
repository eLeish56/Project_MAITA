# Panduan Lengkap Reset Database

## Masalah yang Terjadi
Error: `SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'PR-0003/11/2025'`

Ini terjadi karena ada data lama di database yang masih ada tetapi tabel belum dihapus sepenuhnya, menyebabkan constraint unique pada `pr_number` terlanggal.

## Solusi: Reset Database dari 0

Ada 3 metode yang bisa Anda gunakan:

---

## METODE 1: Menggunakan Laravel Artisan (Rekomendasi - Paling Aman)

### Langkah-langkah:

1. **Buka Terminal/PowerShell** di folder project Anda
2. **Jalankan command berikut secara berurutan:**

```powershell
# Langkah 1: Hapus semua database dan buat fresh
php artisan migrate:fresh

# Langkah 2: (Opsional) Jika ada seeder, jalankan untuk membuat data dummy
php artisan migrate:fresh --seed
```

**Hasil:** 
- ✅ Database akan benar-benar kosong (semua tabel dihapus dan dibuat ulang)
- ✅ Semua data lama akan hilang
- ✅ Auto-increment akan reset ke 0
- ✅ Constraint unique akan fresh

---

## METODE 2: Menggunakan SQL Direct (Jika Artisan Bermasalah)

### Langkah-langkah:

1. **Buka MySQL Command Line atau MySQL Workbench**
2. **Pilih database Anda** (sesuaikan dengan nama database di `.env`)
3. **Jalankan command berikut:**

```sql
-- Hapus constraint foreign key dan tabel
SET FOREIGN_KEY_CHECKS=0;

-- Hapus semua tabel
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS password_reset_tokens;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS personal_access_tokens;
DROP TABLE IF EXISTS suppliers;
DROP TABLE IF EXISTS purchase_requests;
DROP TABLE IF EXISTS purchase_orders;
DROP TABLE IF EXISTS goods_receipts;
DROP TABLE IF EXISTS goods_receipt_items;
DROP TABLE IF EXISTS invoices;
DROP TABLE IF EXISTS invoice_items;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS inventory_movements;
DROP TABLE IF EXISTS inventory_records;
DROP TABLE IF EXISTS inventory_settings;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS transaction_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS marketplace_orders;
DROP TABLE IF EXISTS marketplace_order_items;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS absences;
DROP TABLE IF EXISTS migrations;
DROP TABLE IF EXISTS failed_jobs;

-- Aktifkan kembali constraint
SET FOREIGN_KEY_CHECKS=1;
```

4. **Setelah semua tabel dihapus, jalankan migration:**

```powershell
php artisan migrate
```

---

## METODE 3: Menggunakan Script PHP Otomatis

Saya sudah membuat script yang bisa Anda jalankan:

```powershell
php reset_database.php
```

Script ini akan:
- ✅ Otomatis menghapus semua tabel
- ✅ Reset semua data
- ✅ Jalankan migration fresh
- ✅ Siap digunakan dari 0

---

## Verifikasi Database Sudah Reset

Setelah menjalankan salah satu metode di atas, verifikasi dengan command:

```powershell
# Melihat daftar table
php artisan tinker
>>> DB::select('SHOW TABLES')

# Atau buka MySQL dan lihat table list
SHOW TABLES;
```

Jika hasil kosong atau hanya ada `migrations` table, berarti reset berhasil.

---

## Setelah Reset, Sebelum Menggunakan Sistem

1. **Login dengan akun default** (jika ada seeder yang membuat akun)
2. **Buat PR baru** - sekarang tidak akan ada error duplicate entry
3. **PR number akan auto-generate** dari angka 1 (misalnya: PR-0001/11/2025)

---

## Hal Penting ⚠️

- ⚠️ **Proses ini TIDAK BISA DIKEMBALIKAN** - semua data akan hilang
- ✅ Ini adalah cara yang benar untuk mereset database di development
- ✅ Tidak perlu menghapus folder atau file project
- ✅ Semua bisa di-restore dengan menjalankan migration ulang

---

## Troubleshooting

### Jika masih error saat migrate:
```powershell
# Hapus file cached
php artisan config:clear
php artisan cache:clear

# Kemudian jalankan migrate ulang
php artisan migrate:fresh
```

### Jika database tidak bisa diakses:
- Pastikan MySQL server sedang berjalan
- Cek konfigurasi `.env` sesuai dengan database Anda
- Test koneksi: `php artisan tinker` → `DB::connection()->getPdo()`

---

## Rekomendasi Ke Depannya

Untuk menghindari duplikasi PR number di masa depan:
- ✅ Jangan mengedit PR number secara manual
- ✅ Gunakan auto-generated PR number yang sudah ada di sistem
- ✅ Hindari import data secara manual tanpa validasi

