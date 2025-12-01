# ✅ SETUP CHECKLIST - PRINT & TICK OFF

Print file ini dan tandai saat selesai setiap step.

---

## 📋 PRE-SETUP CHECKLIST

Pastikan sudah ada sebelum memulai:

```
☐ PHP 8.1+ installed
  Command: php --version
  
☐ MySQL/MariaDB installed & running
  Command: mysql --version
  
☐ Composer installed
  Command: composer --version
  
☐ Git installed
  Command: git --version
  
☐ Node.js & npm installed
  Command: node --version
  
☐ Text editor ready (VS Code, etc)

☐ Internet connection (untuk download packages)

☐ MySQL service running
  Windows: Services > MySQL > Start
```

---

## 🚀 SETUP CHECKLIST (10 STEPS)

### **STEP 1: Navigate to Project**
```
Location: D:\Projects\TA_ALIF_TGL28-main
(atau lokasi di mana Anda copy project)

☐ Folder exist
☐ Sudah masuk ke folder di PowerShell
☐ Command: cd D:\Projects\TA_ALIF_TGL28-main
```

**Expected**: Folder terbuka di PowerShell

---

### **STEP 2: Install Composer Dependencies**
```
Command: composer install

☐ Command executed
☐ Waiting for download...
☐ All dependencies installed
☐ No error messages
```

**Expected**: Terminal show "completed successfully"
**Time**: 5-10 minutes

---

### **STEP 3: Copy Environment File**
```
Command: Copy-Item .env.example -Destination .env

☐ Command executed
☐ .env file created
☐ Can see .env in folder
```

**Expected**: `.env` file visible di folder

---

### **STEP 4: Edit Environment File**
```
Command: notepad .env

☐ Notepad opened
☐ Found database section:
  ☐ DB_CONNECTION=mysql
  ☐ DB_HOST=127.0.0.1
  ☐ DB_PORT=3306
  ☐ DB_DATABASE=laravelpos
  ☐ DB_USERNAME=root
  ☐ DB_PASSWORD=(kosong atau sesuai setting)
☐ File saved (Ctrl+S)
☐ Notepad closed
```

**Expected**: .env properly configured with database settings

---

### **STEP 5: Generate Application Key**
```
Command: php artisan key:generate

☐ Command executed
☐ Application key set message shown
☐ No error message
```

**Expected**: "Application key set successfully"

---

### **STEP 6: Create Database**
```
Command: mysql -u root -p
Then in MySQL prompt:
  CREATE DATABASE laravelpos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  EXIT;

☐ MySQL connected
☐ Database created successfully
☐ MySQL exited
```

**Expected**: "Query OK, 1 row affected"

---

### **STEP 7: Fresh Migrate Database**
```
Command: php artisan migrate:fresh

☐ Command executed
☐ Dropping all tables...
☐ All migrations running
☐ Database schema created
☐ No error message
```

**Expected**: Final "Migrated" message tanpa error

---

### **STEP 8: Seed Database**
```
Command: php artisan db:seed

☐ Command executed
☐ Seeding tables...
☐ Data inserted
☐ No error message
```

**Expected**: "Database seeded successfully"

---

### **STEP 9: Build Frontend Assets**
```
Commands:
  npm install
  npm run build

☐ npm install completed
☐ npm run build completed
☐ No error messages
☐ dist/ folder created (or build output)
```

**Expected**: Build complete without errors

---

### **STEP 10: Create Storage Link & Start Server**
```
Commands:
  php artisan storage:link
  php artisan serve

☐ Storage link created
☐ Laravel serve running
☐ See message: "Laravel development server started"
☐ No port conflicts
☐ Server accessible at http://127.0.0.1:8000
```

**Expected**: "Laravel development server started at [http://127.0.0.1:8000]"

---

## 🌐 POST-SETUP VERIFICATION

Setelah server running:

```
☐ Open browser
☐ Navigate to: http://127.0.0.1:8000
☐ Website loading (not error 500)
☐ See login page or dashboard

☐ Click "Transaction" atau navigate to /transaction
☐ Transaction page visible
☐ Can see UI elements (buttons, inputs)

☐ Try login:
  Email: admin@example.com
  Password: password
☐ Login successful
☐ Dashboard/Home page loads

☐ Can navigate between pages
☐ No console errors in browser (F12)
```

---

## 📊 DATABASE VERIFICATION

Verify database setup:

```
Open new PowerShell/Command Prompt (keep server running):

Command: mysql -u root -p laravelpos

In MySQL:
☐ SHOW TABLES; (see list of tables)
☐ SELECT COUNT(*) FROM users; (see admin user)
☐ SELECT COUNT(*) FROM items; (see products)
☐ SELECT COUNT(*) FROM categories; (see categories)
☐ EXIT;
```

**Expected**: Dapat melihat data dari seeder

---

## 🎯 FINAL CHECKLIST

```
☐ Project fully setup di laptop
☐ Database initialized dengan data
☐ PHP artisan serve running
☐ Website accessible via browser
☐ Login works dengan default credentials
☐ Can navigate different pages
☐ No major errors in console/logs
☐ Ready for development
```

---

## ⚠️ IF ANY STEP FAILED

```
☐ Read error message carefully
☐ Note the error (copy-paste untuk reference)
☐ Open SETUP_GUIDE.md or DATABASE_RESET_GUIDE.md
☐ Search error keyword di troubleshooting section
☐ Follow suggested solution
☐ Retry the failed step
```

---

## 🔄 COMMON FAILURE POINTS & QUICK FIXES

### **If Step 2 fails (Composer install)**
```
Symptom: "composer: command not found"
Quick Fix: 
  ☐ Check: composer --version
  ☐ If not work: Reinstall Composer
  ☐ Add to PATH if needed
```

### **If Step 5 fails (Key generate)**
```
Symptom: "Application key not set" atau duplicate key error
Quick Fix:
  ☐ Check .env file
  ☐ Try: php artisan key:generate --force
  ☐ Proceed ke langkah berikutnya
```

### **If Step 6 fails (Create database)**
```
Symptom: "Can't connect to MySQL server"
Quick Fix:
  ☐ Check: MySQL service running (Services.msc)
  ☐ Check: credentials di .env benar
  ☐ Try: net start MySQL80 (di PowerShell as Admin)
  ☐ Or: Restart MySQL service
```

### **If Step 7 fails (Migrate)**
```
Symptom: "Database connection error" atau "table already exists"
Quick Fix:
  ☐ Verify database exists: CREATE DATABASE laravelpos;
  ☐ Try: php artisan migrate:fresh --force
  ☐ Or: php artisan migrate:reset
  ☐ Then: php artisan migrate
```

### **If Step 9 fails (npm build)**
```
Symptom: npm error atau build failed
Quick Fix:
  ☐ Delete: node_modules folder
  ☐ Delete: package-lock.json
  ☐ Run: npm install
  ☐ Run: npm run build again
```

### **If Step 10 fails (Server)**
```
Symptom: "Address already in use" atau port conflicts
Quick Fix:
  ☐ Change port: php artisan serve --port=8001
  ☐ Or: Kill process on port 8000
  ☐ powershell: netstat -ano | findstr :8000
  ☐ Then: taskkill /PID <PID> /F
```

---

## 📱 DEVICE STATUS AT EACH STEP

```
STEP 1: ☐ PowerShell at project folder
STEP 2: ☐ Composer packages installed
STEP 3: ☐ .env file exists
STEP 4: ☐ .env configured
STEP 5: ☐ APP_KEY set
STEP 6: ☐ Database created
STEP 7: ☐ Database schema ready
STEP 8: ☐ Initial data seeded
STEP 9: ☐ Assets built
STEP 10:☐ Server running
```

---

## ⏱️ TIMING TRACKER

Record actual time untuk reference:

```
Start Time: __________ (jam:menit)
Step 1: __________ (selesai jam:menit)
Step 2: __________ (selesai jam:menit) - Duration: ___min
Step 3: __________ (selesai jam:menit)
Step 4: __________ (selesai jam:menit)
Step 5: __________ (selesai jam:menit)
Step 6: __________ (selesai jam:menit)
Step 7: __________ (selesai jam:menit)
Step 8: __________ (selesai jam:menit)
Step 9: __________ (selesai jam:menit) - Duration: ___min
Step 10:__________ (selesai jam:menit)

Total Time: __________ minutes
Expected: 15-60 minutes
Status: ☐ On track ☐ Slower than expected
```

---

## 📝 NOTES & COMMENTS

```
Any issues encountered:
_________________________________________
_________________________________________
_________________________________________

Solutions tried:
_________________________________________
_________________________________________

Final status:
☐ Setup successful
☐ Setup partially done - continue next time
☐ Need help - check documentation

```

---

## ✅ SIGN-OFF

```
Setup Date: ____________________
Setup by: ____________________
Machine: Windows / Mac / Linux
PHP Version: ____________________
MySQL Version: ____________________
Composer Version: ____________________

Final Status: 
☐ SETUP COMPLETE ✅
☐ SETUP PARTIAL - needs followup
☐ SETUP FAILED - troubleshooting needed
```

---

## 🎯 AFTER SUCCESSFUL SETUP

```
Next actions:
☐ Read documentation in DOCUMENTATION_INDEX.md
☐ Explore database schema
☐ Check Controllers & Models
☐ Review current features
☐ Plan modifications/customizations
☐ Start development work
```

---

## 📞 REFERENCE INFORMATION

Keep this info handy:

```
Project URL: http://127.0.0.1:8000
Database: laravelpos
DB Host: 127.0.0.1
DB User: root
Admin Email: admin@example.com
Admin Pass: password
PhpMyAdmin: http://localhost/phpmyadmin

Documentation:
- Quick Start: QUICK_START.md
- Full Guide: SETUP_GUIDE.md
- Database: DATABASE_RESET_GUIDE.md
- Index: DOCUMENTATION_INDEX.md
```

---

## 🎉 SETUP COMPLETE!

Jika semua checklist sudah diisi dan semua step sukses:

**🎊 CONGRATULATIONS! 🎊**

Project Anda sudah siap untuk:
✅ Development
✅ Testing
✅ Customization
✅ Deployment

---

**Print & Keep for reference!**

**Last Updated**: 24 November 2025

