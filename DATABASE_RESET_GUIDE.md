# 🔄 Database Reset - PHPMyAdmin & MySQL

Panduan lengkap untuk **merestart data** di PHPMyAdmin dan MySQL.

---

## 📌 Pilih Metode Sesuai Kebutuhan

### **METODE 1: Command Line (RECOMMENDED) ⭐**

Paling cepat dan reliable. Jalankan di PowerShell:

```powershell
# Method 1A: Fresh migrate + seed (BEST)
php artisan migrate:fresh --seed

# Method 1B: Fresh migrate only (no seed)
php artisan migrate:fresh

# Method 1C: Rollback + migrate (safe)
php artisan migrate:rollback --step=999
php artisan migrate
php artisan db:seed
```

**Output yang diharapkan:**
```
Dropped all tables successfully.
Migrating: 0001_01_01_000000_create_users_table
Migrating: 0001_01_01_000000_create_items_table
...
Migrated:  2025_11_11_224914_create_password_reset_tokens_table (175ms)
Database seeded successfully.
```

✅ **Selesai! Database sudah fresh dan penuh data awal.**

---

### **METODE 2: PHPMyAdmin GUI (User-Friendly)**

#### **Step 2.1: Buka PHPMyAdmin**
```
Alamat: http://localhost/phpmyadmin
Username: root
Password: (kosong atau sesuai setting Anda)
```

#### **Step 2.2: Pilih Database `laravelpos`**
- Klik database `laravelpos` di sidebar kiri
- Atau klik tab "Databases" jika tidak ada

#### **Step 2.3: Drop Database (Hapus Semuanya)**

**Cara A - Hapus semua table:**
1. Klik tombol "Drop" di sebelah nama database
2. Pilih "Yes, proceed"

**Cara B - Hapus table satu per satu:**
1. Pilih semua table dengan checkbox
2. Klik dropdown "With selected"
3. Pilih "Drop" 
4. Klik "Go"

#### **Step 2.4: Buat Database Baru**

1. Klik tab "SQL"
2. Copy-paste query ini:
```sql
CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
3. Klik "Go"

#### **Step 2.5: Re-run Migration via Command Line**

Kembali ke PowerShell:
```powershell
php artisan migrate
php artisan db:seed
```

---

### **METODE 3: PHPMyAdmin - Truncate Tables (Keep Structure)**

Jika hanya ingin **menghapus data tapi keep table structure**:

#### **Via PHPMyAdmin:**

1. Buka PHPMyAdmin: `http://localhost/phpmyadmin`
2. Pilih database `laravelpos`
3. Klik tab "SQL"
4. Copy-paste query ini:

```sql
-- Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;

-- Truncate all data tables
TRUNCATE TABLE transactions;
TRUNCATE TABLE transaction_details;
TRUNCATE TABLE carts;
TRUNCATE TABLE cart_items;
TRUNCATE TABLE purchase_orders;
TRUNCATE TABLE purchase_order_items;
TRUNCATE TABLE purchase_requests;
TRUNCATE TABLE purchase_request_items;
TRUNCATE TABLE marketplace_orders;
TRUNCATE TABLE marketplace_order_items;
TRUNCATE TABLE goods_receipts;
TRUNCATE TABLE goods_receipt_items;
TRUNCATE TABLE invoices;
TRUNCATE TABLE inventory_movements;
TRUNCATE TABLE inventory_records;
TRUNCATE TABLE users;
TRUNCATE TABLE items;
TRUNCATE TABLE categories;
TRUNCATE TABLE suppliers;
TRUNCATE TABLE payment_methods;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
```

5. Klik "Go"

✅ **Semua data dihapus, table structure tetap**

---

### **METODE 4: MySQL Command Line**

Untuk yang suka CLI pure:

```powershell
# Masuk ke MySQL
mysql -u root -p

# Jika diminta password, ketik password Anda (atau tekan Enter jika kosong)
```

**Di MySQL prompt, jalankan:**

#### **Option A: Drop & Recreate Database**
```sql
DROP DATABASE IF EXISTS laravelpos;
CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

Kemudian di PowerShell:
```powershell
php artisan migrate
php artisan db:seed
```

#### **Option B: Truncate All Tables**
```sql
USE laravelpos;
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE transactions;
TRUNCATE TABLE transaction_details;
TRUNCATE TABLE carts;
TRUNCATE TABLE cart_items;
-- ... (list semua table)

SET FOREIGN_KEY_CHECKS = 1;
EXIT;
```

---

## 🎯 REKOMENDASI BERDASARKAN SKENARIO

### ✅ Scenario 1: Setup Baru (Fresh Install)
```powershell
php artisan migrate:fresh --seed
```
**Waktu**: 2 menit
**Result**: Database kosong + data seeder

---

### ✅ Scenario 2: Development (Reset untuk testing)
```powershell
php artisan migrate:fresh --seed
```
**Waktu**: 2 menit
**Result**: Fresh database dengan test data

---

### ✅ Scenario 3: Production (Keep struktur, hapus data)
```sql
-- Di PHPMyAdmin SQL tab
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE transactions;
TRUNCATE TABLE carts;
-- ... (truncate tables penting saja)
SET FOREIGN_KEY_CHECKS = 1;
```
**Waktu**: 1 menit
**Result**: Data terhapus, struktur tetap

---

### ✅ Scenario 4: Seeding Ulang (sudah ada structure)
```powershell
php artisan db:seed
```
**Waktu**: 1 menit
**Result**: Hanya populate data, tidak drop table

---

## ⚙️ Database Seeder - Apa yang di-seed?

Ketika jalankan `php artisan db:seed`, data ini akan dibuat:

**File**: `database/seeders/DatabaseSeeder.php`

Biasanya include:
- ✅ Admin user (`admin@example.com`)
- ✅ Categories (default categories untuk items)
- ✅ Items (product list)
- ✅ Payment methods (Tunai, Transfer, etc)
- ✅ Suppliers (jika ada)
- ✅ Other master data

---

## 🔍 Verify Database Status

### Via PHPMyAdmin:
1. Buka `http://localhost/phpmyadmin`
2. Pilih database `laravelpos`
3. Lihat table list di sidebar
4. Klik tab "Structure" untuk lihat schema

### Via MySQL CLI:
```powershell
mysql -u root -p laravelpos
```

```sql
-- List all tables
SHOW TABLES;

-- Count records di table tertentu
SELECT COUNT(*) FROM transactions;
SELECT COUNT(*) FROM users;

-- Show table structure
DESCRIBE users;
DESCRIBE transactions;

-- Exit
EXIT;
```

---

## ⚠️ BACKUP BEFORE RESET

### Backup Database sebelum reset:

```powershell
# Export database ke file SQL
mysqldump -u root -p laravelpos > D:\backup_laravelpos_$(Get-Date -Format 'yyyyMMdd_HHmmss').sql

# Jika diminta password, ketik password MySQL Anda
```

**File backup akan tersimpan di `D:\backup_laravelpos_20251124_143022.sql`**

### Restore dari backup:
```powershell
mysql -u root -p laravelpos < D:\backup_laravelpos_20251124_143022.sql
```

---

## 🆘 TROUBLESHOOTING RESET

### Problem 1: "Foreign key constraint failed"
**Penyebab**: Ada constraint yang prevent truncate
**Solusi**: Tambahkan di awal query:
```sql
SET FOREIGN_KEY_CHECKS = 0;
-- ... truncate commands ...
SET FOREIGN_KEY_CHECKS = 1;
```

### Problem 2: "Table doesn't exist"
**Penyebab**: Table sudah dihapus tapi query masih reference
**Solusi**: Skip table yang tidak ada, atau jalankan migrate ulang

### Problem 3: "Access denied for user 'root'@'localhost'"
**Penyebab**: Password/username salah
**Solusi**: 
- Verifikasi MySQL credentials di `.env`
- Atau reset MySQL password

### Problem 4: "ERROR 2003: Can't connect to MySQL server"
**Penyebab**: MySQL service tidak running
**Solusi**: 
```powershell
# Windows: Buka Services (Win+R > services.msc)
# Cari MySQL atau MariaDB, klik Start

# Atau restart:
Restart-Service -Name MySQL80
```

---

## 📊 Database Status Checker Script

Jalankan di PowerShell untuk check status database:

```powershell
# Buat file check_db.ps1 dengan content:
$query = "SELECT COUNT(*) as total_users FROM users;"
mysql -u root -e "USE laravelpos; $query"

$query2 = "SELECT COUNT(*) as total_items FROM items;"
mysql -u root -e "USE laravelpos; $query2"

$query3 = "SHOW TABLES;"
mysql -u root -e "USE laravelpos; $query3"
```

Simpan dan jalankan:
```powershell
.\check_db.ps1
```

---

## 🎯 QUICK REFERENCE - Reset Commands

```powershell
# 1️⃣ Fresh Start (DROP + CREATE + MIGRATE + SEED)
php artisan migrate:fresh --seed

# 2️⃣ Keep Structure (TRUNCATE DATA ONLY)
php artisan tinker
DB::statement('SET FOREIGN_KEY_CHECKS = 0');
DB::statement('TRUNCATE transactions');
DB::statement('TRUNCATE carts');
exit

# 3️⃣ Seed Data Only
php artisan db:seed

# 4️⃣ Rollback All
php artisan migrate:rollback --step=999

# 5️⃣ Show Migrations Status
php artisan migrate:status

# 6️⃣ Check Database
mysql -u root -p laravelpos
```

---

## ✅ Verification Checklist

Setelah reset, verify:

- [ ] Database `laravelpos` exists
- [ ] Semua table ter-create (check di PHPMyAdmin)
- [ ] Admin user exist (check users table)
- [ ] Default data ter-seed (check items, categories)
- [ ] Aplikasi bisa diakses: `http://127.0.0.1:8000`
- [ ] Bisa login dengan `admin@example.com` / `password`
- [ ] Halaman transaction load dengan benar

---

## 📞 Bantuan Lebih Lanjut

Jika ada yang bingung:
1. Check logs: `storage/logs/laravel.log`
2. Jalankan: `php artisan migrate:status`
3. Verify `.env` database credentials
4. Restart MySQL service

---

**Last Updated**: 24 November 2025
**Status**: ✅ Ready to use

