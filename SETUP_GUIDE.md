# Panduan Setup Project Laravel POS di Laptop Baru

## 📋 Prerequisites (Yang harus sudah terinstall)

Pastikan laptop Anda sudah punya:
- ✅ PHP 8.1+ 
- ✅ Composer
- ✅ MySQL/MariaDB
- ✅ Git
- ✅ Node.js & npm (untuk Vite assets)
- ✅ Text Editor (VS Code, Laravel IDE, etc)

### Cek di PowerShell:
```powershell
# Cek versi PHP
php --version

# Cek Composer
composer --version

# Cek MySQL
mysql --version

# Cek Node
node --version
npm --version
```

---

## 🚀 LANGKAH 1: Clone/Copy Project

### Opsi A: Clone dari GitHub
```powershell
# Buka PowerShell di folder yang diinginkan
cd D:\Projects  # atau C:\Users\YourName\Documents\Projects

# Clone repository
git clone https://github.com/tumbalone178/tanggal_11_ta.git

# Masuk ke folder project
cd tanggal_11_ta
```

### Opsi B: Copy Manual dari Flash/External Drive
```powershell
# Copy folder project ke location yang diinginkan
# Contoh: D:\Projects\TA_ALIF_TGL28-main

cd D:\Projects\TA_ALIF_TGL28-main
```

---

## 📦 LANGKAH 2: Install Dependencies

```powershell
# Install PHP dependencies (composer)
composer install

# Jika ada error tentang memory, gunakan:
composer install --no-interaction --no-dev --optimize-autoloader
```

**⏱️ Estimasi waktu**: 5-10 menit (tergantung koneksi internet)

---

## 🔧 LANGKAH 3: Setup Environment File

```powershell
# Copy .env.example ke .env
Copy-Item .env.example -Destination .env

# Atau jika sudah ada, backup terlebih dahulu:
Copy-Item .env -Destination .env.backup
```

### Edit file `.env`:
Buka file `.env` dengan text editor dan sesuaikan:

```env
APP_NAME="Teaching Factory POS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravelpos          # ← Nama database Anda
DB_USERNAME=root                 # ← Username MySQL
DB_PASSWORD=                      # ← Password MySQL (kosong jika default)

MAIL_MAILER=log
QUEUE_CONNECTION=sync
```

**❓ Jika Anda tidak tahu password MySQL:**
- Default biasanya: username=`root`, password=kosong (blank)
- Atau cek di phpMyAdmin settings Anda

---

## 🗄️ LANGKAH 4: Generate Application Key

```powershell
# Generate key untuk encryption
php artisan key:generate

# Jika ada warning "Key already exists", itu OK
```

---

## 📊 LANGKAH 5: Setup Database

### Step 5.1: Buat Database Baru
```powershell
# Masuk ke MySQL CLI
mysql -u root -p

# Jika diminta password, ketikkan password MySQL Anda (atau tekan Enter jika kosong)
```

**Di MySQL prompt:**
```sql
-- Buat database baru (hapus yang lama jika ada)
DROP DATABASE IF EXISTS laravelpos;
CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Verify
SHOW DATABASES;

-- Exit MySQL
EXIT;
```

### Step 5.2: Run Database Migration & Seeding

```powershell
# Fresh migrate (delete semua table, recreate dari migration)
php artisan migrate:fresh

# Atau jika ada error, try:
php artisan migrate:fresh --force

# Seed data awal (populate dengan data default)
php artisan db:seed
```

**✅ Output yang diharapkan:**
```
Migrating: 0001_01_01_000000_create_users_table
Migrating: 0001_01_01_000000_create_items_table
...
Migrated:  0001_01_01_000000_create_users_table
...
Database seeded successfully.
```

---

## 🔑 LANGKAH 6: Create Storage Link

```powershell
# Buat symbolic link untuk storage (agar gambar bisa di-akses)
php artisan storage:link

# Jika sudah ada, delete dulu:
Remove-Item public\storage
php artisan storage:link
```

---

## 🎨 LANGKAH 7: Build Frontend Assets

```powershell
# Install npm dependencies
npm install

# Build Vite assets (untuk production, fast build)
npm run build

# Atau jika ingin development mode (dengan hot reload):
npm run dev
```

**⏱️ Estimasi waktu**: 3-5 menit

---

## ✅ LANGKAH 8: Verify Setup dengan Test Serve

```powershell
# Start Laravel development server
php artisan serve

# Output yang diharapkan:
# Laravel development server started at [http://127.0.0.1:8000]
```

---

## 🌐 LANGKAH 9: Akses Aplikasi

Buka browser dan akses:

```
http://127.0.0.1:8000/transaction
```

### Default Login Credentials:

**Admin Account:**
- Email: `admin@example.com`
- Password: `password`

**Atau cek di database seeder** (file: `database/seeders/DatabaseSeeder.php`)

---

## 🔄 LANGKAH 10: Restart Data di phpMyAdmin

### Jika ingin reset semua data ke state awal:

```powershell
# Method 1: Fresh Migrate + Seed (Recommended)
php artisan migrate:fresh --seed

# Method 2: Manual dengan SQL
php artisan tinker
>>> DB::statement('TRUNCATE TABLE transactions');
>>> DB::statement('TRUNCATE TABLE carts');
>>> exit
```

### Via phpMyAdmin:

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Pilih database `laravelpos`
3. Klik tab "SQL"
4. Copy-paste script di bawah:

```sql
-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- Truncate all tables
TRUNCATE TABLE transactions;
TRUNCATE TABLE transaction_details;
TRUNCATE TABLE carts;
TRUNCATE TABLE cart_items;
TRUNCATE TABLE purchase_orders;
TRUNCATE TABLE purchase_order_items;
TRUNCATE TABLE marketplace_orders;
TRUNCATE TABLE marketplace_order_items;
TRUNCATE TABLE goods_receipts;
TRUNCATE TABLE goods_receipt_items;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
```

5. Klik "Go" untuk execute

---

## 🐛 TROUBLESHOOTING

### Problem 1: "SQLSTATE[HY000] [2002] No such file or directory"
**Solusi**: Pastikan MySQL/MariaDB service sudah running
```powershell
# Di Windows:
# Buka Services (Win+R > services.msc)
# Cari "MySQL" atau "MariaDB", klik "Start"

# Atau restart service:
Restart-Service -Name MySQL80  # atau MariaDB sesuai versi Anda
```

### Problem 2: "php: command not found"
**Solusi**: PHP belum di PATH
```powershell
# Cek lokasi PHP
where php

# Jika tidak ada, tambahkan ke PATH Environment Variables
# Control Panel > System > Advanced Settings > Environment Variables
```

### Problem 3: "composer: command not found"
**Solusi**: Composer belum terinstall atau belum di PATH
```powershell
# Download dari: https://getcomposer.org/
# Atau reinstall dengan installer
```

### Problem 4: Error saat `npm install`
**Solusi**: Delete `node_modules` dan package-lock.json, install ulang
```powershell
Remove-Item node_modules -Recurse -Force
Remove-Item package-lock.json
npm install
```

### Problem 5: "The key already exists" saat key:generate
**Solusi**: Itu bukan error, key sudah ada. Proceed ke langkah berikutnya

---

## 📝 CHECKLIST SETUP

- [ ] Clone/Copy project ke laptop
- [ ] composer install (semua dependencies terinstall)
- [ ] Copy .env.example ke .env
- [ ] Edit .env sesuai konfigurasi lokal
- [ ] php artisan key:generate
- [ ] Buat database baru di MySQL
- [ ] php artisan migrate:fresh
- [ ] php artisan db:seed
- [ ] php artisan storage:link
- [ ] npm install
- [ ] npm run build
- [ ] php artisan serve (test akses)
- [ ] ✅ Login dan test aplikasi

---

## 🚀 QUICK START SUMMARY

```powershell
# Paste semua baris ini satu per satu di PowerShell

# 1. Navigate ke project
cd D:\Projects\TA_ALIF_TGL28-main

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
Copy-Item .env.example -Destination .env
# ✏️ EDIT .env dengan DB credentials Anda

# 4. Generate key
php artisan key:generate

# 5. Setup database
# ✏️ Buat database di MySQL terlebih dahulu

# 6. Migrate & seed
php artisan migrate:fresh
php artisan db:seed

# 7. Build assets
npm run build

# 8. Create storage link
php artisan storage:link

# 9. Start server
php artisan serve

# ✅ Akses: http://127.0.0.1:8000
```

---

## 🔗 Useful Commands Reference

```powershell
# Development
php artisan serve                    # Start dev server

# Database
php artisan migrate                  # Run migrations
php artisan migrate:fresh            # Fresh migrate (delete all data)
php artisan migrate:rollback         # Rollback last migration
php artisan db:seed                  # Seed database

# Cache & Optimization
php artisan cache:clear              # Clear application cache
php artisan view:clear               # Clear view cache
php artisan config:cache             # Cache configuration
php artisan optimize                 # Optimize for production

# Assets
npm run dev                           # Development (with hot reload)
npm run build                         # Build for production

# Debugging
php artisan tinker                   # Interactive shell
php artisan route:list               # List all routes
```

---

## 💡 Tips & Best Practices

1. **Jangan edit .env di production** - gunakan environment variables
2. **Backup .env sebelum edit** - jangan sampai config ilang
3. **Gunakan `php artisan migrate:fresh`** - untuk reset data awal
4. **npm run build** setelah modifikasi CSS/JS - supaya ter-compile
5. **Jalankan `php artisan optimize`** sebelum production

---

## 📞 Jika Ada Error

**Backup informasi untuk debugging:**
1. Jalankan: `php artisan --version`
2. Jalankan: `php -v`
3. Jalankan: `composer --version`
4. Check file: `storage/logs/laravel.log`
5. Share error message dengan error trace

---

**Status**: ✅ READY TO DEPLOY
**Last Updated**: 24 November 2025
**Tested on**: Laravel 11, PHP 8.1+, MySQL 8.0

