# 🧪 PANDUAN LENGKAP TESTING SISTEM POS TERINTEGRASI

## Daftar Isi
1. [Overview](#overview)
2. [Tools Yang Tersedia](#tools-yang-tersedia)
3. [Persiapan Testing](#persiapan-testing)
4. [Cara Menggunakan Setiap Tool](#cara-menggunakan-setiap-tool)
5. [Workflow Testing](#workflow-testing)
6. [Tips & Trik](#tips--trik)
7. [FAQ](#faq)

---

## Overview

Anda memiliki **52 test cases** untuk blackbox testing:
- **Customer**: 12 test cases (HIGH priority)
- **Cashier**: 13 test cases (HIGH priority)
- **Admin**: 11 test cases (MEDIUM priority)
- **Supervisor**: 16 test cases (HIGH priority)

**Total: 52 test cases** yang perlu dijalankan

---

## Tools Yang Tersedia

### 1. **HTML Interactive Checklist** (Recommended untuk real-time)
- **File**: `public/testing-checklist.html`
- **Cara Akses**: `http://localhost:8000/testing-checklist.html`
- **Fitur**: 
  - ✅ Real-time statistik
  - ✅ Filter by actor/priority/status
  - ✅ Export ke CSV
  - ✅ Add notes per test
  - ✅ Auto-calculate pass rate

### 2. **Excel Template** (Recommended untuk offline)
- **File**: `TESTING_TEMPLATE_EXCEL.md`
- **Cara**: Copy data ke Excel dengan struktur di panduan
- **Fitur**:
  - ✅ Data validation dropdown
  - ✅ Conditional formatting
  - ✅ Formula untuk statistik
  - ✅ Bisa share via email

### 3. **Report Generator Script** (Automated)
- **File**: `generate-testing-report.php`
- **Cara Jalankan**: `php generate-testing-report.php`
- **Output**:
  - testing-report.html
  - testing-report.csv
  - testing-report.json
  - TESTING_EXECUTION_GUIDE.md

### 4. **Dokumentasi Testing Plan**
- **File**: `TESTING_PLAN_BLACKBOX.md`
- **Isi**: Detail setiap test case dengan expected result

---

## Persiapan Testing

### Checklist Persiapan:

```
[ ] 1. Setup Database Fresh
    - php artisan migrate:fresh --seed
    - atau gunakan RESET_DATABASE_FRESH.md

[ ] 2. Siapkan Test Users
    - Admin user (email: admin@test.com, password: password)
    - Cashier user (email: cashier@test.com, password: password)
    - Supervisor user (email: supervisor@test.com, password: password)
    - Customer user (email: customer@test.com, password: password)

[ ] 3. Verifikasi Data Master
    - Produk minimal 10 items ✅
    - Kategori minimal 3 ✅
    - Supplier minimal 2 ✅
    - Payment methods minimal 3 ✅

[ ] 4. Siapkan Tools
    - Browser modern (Chrome/Firefox)
    - Excel atau Google Sheets
    - Screenshot tool (Win+Shift+S untuk Windows)
    - Text editor untuk notes

[ ] 5. Tentukan Tester
    - Nama tester: _______________
    - Email: _______________
    - Tanggal mulai: _______________
```

---

## Cara Menggunakan Setiap Tool

### OPSI 1: HTML Interactive Checklist (RECOMMENDED)

#### Step 1: Akses Tool
```
Buka browser → http://localhost:8000/testing-checklist.html
```

#### Step 2: Isi Informasi Tester
```
Klik field "Tester Name" di footer
Input nama Anda: "Alif Muhammad"
```

#### Step 3: Mulai Testing
```
1. Pilih tab aktor (Customer, Cashier, Admin, atau Supervisor)
2. Jalankan test sesuai deskripsi
3. Dokumentasikan hasil:
   - Status: PASS / FAIL / PENDING
   - Tanggal: (auto-filled)
   - Notes: (opsional) Deskripsi masalah jika FAIL
4. Lihat progress di statistik atas
```

#### Step 4: Export Hasil
```
Klik tombol "Export CSV"
File: testing-report-[timestamp].csv akan download
Share ke stakeholder
```

### OPSI 2: Excel Offline

#### Step 1: Buat File Excel
```
1. Buka Excel baru
2. Buat sheet bernama "Testing"
3. Copy struktur header dari TESTING_TEMPLATE_EXCEL.md:
   A | Test ID | B | Fitur | C | Deskripsi | ...
```

#### Step 2: Copy Test Data
```
Copy-paste data dari TESTING_TEMPLATE_EXCEL.md:
- CUSTOMER (12 rows)
- CASHIER (13 rows)
- ADMIN (11 rows)
- SUPERVISOR (16 rows)
Total: 52 rows
```

#### Step 3: Setup Validasi
```
Kolom F (Status):
- Klik kolom F
- Data → Validity/Validation → Custom
- Allow: List
- Source: PENDING,PASS,FAIL
```

#### Step 4: Setup Conditional Formatting
```
Kolom F (Status):
- Select range F2:F53
- Format → Conditional Formatting
  - PASS → Green (#10b981)
  - FAIL → Red (#ef4444)
  - PENDING → Yellow (#f59e0b)
```

#### Step 5: Jalankan Testing
```
1. Filter by aktor: Data → AutoFilter
2. Test satu per satu
3. Mark status dan tanggal
4. Add notes jika ada issue
5. Lihat progress di summary sheet
```

### OPSI 3: Auto-Generate Reports

#### Step 1: Generate Reports
```bash
cd /path/to/project
php generate-testing-report.php
```

#### Output Files:
```
✅ testing-report.html         (Printable HTML)
✅ testing-report.csv          (Excel import)
✅ testing-report.json         (Data export)
✅ TESTING_EXECUTION_GUIDE.md  (Markdown checklist)
```

#### Step 2: Edit & Fill
```
1. Open testing-report.html di browser
2. Print jika perlu hardcopy
3. Manual fill status untuk setiap test
4. Tanda [ ] untuk hasil
```

---

## Workflow Testing

### Fase 1: Setup (30 menit)
```
1. Reset database: php artisan migrate:fresh --seed
2. Setup test users
3. Verify data master
4. Pilih tool (HTML atau Excel)
5. Input nama tester
```

### Fase 2: Testing Customer (60 menit)
```
Timeline: 12 test cases ≈ 5 menit per test

Test Cases:
1. C-01: Register Account (5 menit)
2. C-02: Login (5 menit)
3. C-03: Browse Products (5 menit)
4. C-04: Search Products (5 menit)
5. C-05: View Product Details (5 menit)
6. C-06: Add to Cart (5 menit)
7. C-07: View Cart (3 menit)
8. C-08: Update Cart Quantity (3 menit)
9. C-09: Remove from Cart (3 menit)
10. C-10: Checkout (5 menit)
11. C-11: Process Payment (5 menit)
12. C-12: View Orders (5 menit)

Hasil diharapkan: 12/12 PASS ✅
```

### Fase 3: Testing Cashier (65 menit)
```
Timeline: 13 test cases ≈ 5 menit per test

Test Cases:
1. K-01: Login
2. K-02: Create Transaction
3. K-03: Add Items (POS)
4. K-04: Check Stock
5. K-05: Update Item Quantity
6. K-06: Remove Item from Cart
7. K-07: Select Payment Method
8. K-08: Complete Transaction
9. K-09: Print Receipt
10. K-10: View Transactions
11. K-11: Process Online Orders
12. K-12: Process Marketplace Orders
13. K-13: Logout

Hasil diharapkan: 13/13 PASS ✅
```

### Fase 4: Testing Admin (55 menit)
```
Timeline: 11 test cases ≈ 5 menit per test

Test Cases:
1. A-01: Login
2. A-02: Create Item
3. A-03: Edit Item
4. A-04: Delete Item
5. A-05: Upload Item Image
6. A-06: Manage Categories
7. A-07: Manage Suppliers
8. A-08: Manage Customers
9. A-09: View Sales Reports
10. A-10: Filter Reports
11. A-11: Export Reports

Hasil diharapkan: 11/11 PASS ✅
```

### Fase 5: Testing Supervisor (80 menit)
```
Timeline: 16 test cases ≈ 5 menit per test

Test Cases:
1. S-01: Login
2. S-02: Manage Users
3. S-03: Assign Roles
4. S-04: Create PR
5. S-05: Approve PR
6. S-06: Reject PR
7. S-07: Create PO
8. S-08: Edit PO Prices
9. S-09: Mark PO as Sent
10. S-10: Create Goods Receipt
11. S-11: Create Invoice
12. S-12: Mark Invoice Paid
13. S-13: View Stock Movement
14. S-14: View Procurement Dashboard
15. S-15: View Reports
16. S-16: Logout

Hasil diharapkan: 16/16 PASS ✅
```

### Fase 6: Bug Documentation (30 menit)
```
Untuk setiap FAIL:
1. Catat Test ID
2. Catat error message
3. Screenshot (Win+Shift+S)
4. Detailed steps to reproduce
5. Expected vs Actual result
6. Severity level (Critical/High/Medium/Low)
```

### Fase 7: Reporting (30 menit)
```
1. Export hasil dari tool
2. Compile bug list
3. Calculate pass rate
4. Write summary
5. Deliver to stakeholder
```

---

## Tips & Trik

### 1. Testing Efisien
```
✅ Test HIGH priority dulu
✅ Test critical paths (login → cart → payment)
✅ Reuse test data untuk multiple tests
✅ Test di berbagai kondisi (valid/invalid input)
✅ Perhatikan edge cases
```

### 2. Screenshot Evidence
```
Ambil screenshot untuk:
- Successful completion (green checkmark, success message)
- Error scenarios (error message, validation)
- Data integrity (data masuk dan tersimpan correct)

Tools:
- Windows: Win + Shift + S
- Mac: Cmd + Shift + 4
- Linux: Print Screen
```

### 3. Notes Best Practice
```
❌ BURUK:    "tidak bisa"
✅ BAIK:    "Field email tidak validasi format (accept: abc@def)"

❌ BURUK:    "error di payment"
✅ BAIK:    "Payment method dropdown tidak menampilkan semua metode (hanya 2 dari 5)"

❌ BURUK:    "crash"
✅ BAIK:    "500 error di /transaction/complete - SQL error di qty update"
```

### 4. Optimasi Waktu
```
Jika ada 52 tests @ 5 menit per test:
- Worst case: 260 menit (4.5 jam) ❌
- Best case: Parallel testing dengan 2-3 tester: 90-120 menit ✅

Strategi:
- Alif: Testing Customer + Cashier
- Tim 2: Testing Admin + Supervisor
- Koordinasi timing untuk shared resources
```

### 5. Testing Checklist
```
Sebelum mark PASS:
[ ] Feature berfungsi sesuai requirement
[ ] Tidak ada error message
[ ] Database update correct
[ ] UI feedback jelas (success message)
[ ] Navigasi bekerja proper
[ ] Data integrity terjaga
```

---

## FAQ

### Q1: Berapa lama estimasi testing total?
**A**: 
- Setup: 30 menit
- Testing (4-5 fase): 260 menit (4.3 jam)
- Documentation & bugs: 60 menit
- **Total: ~6 jam untuk 1 tester**
- Jika 3 tester parallel: ~2-3 jam

### Q2: Bagaimana jika test FAIL?
**A**: 
1. Dokumentasikan di notes
2. Ambil screenshot
3. Catat step to reproduce
4. Bug report jika severity high
5. Lanjut test berikutnya (jangan block)
6. Verify ulang setelah fix

### Q3: Harus test di device apa?
**A**: 
- **Desktop/Laptop**: Chrome atau Firefox latest
- **Mobile** (optional): Chrome mobile untuk marketplace view
- **OS**: Windows/Mac/Linux (sesuai dev environment)

### Q4: Bagaimana handle duplicate test results?
**A**: 
Jika ada multiple tester:
```
Use Excel dengan naming:
- testing-report-alif.xlsx
- testing-report-team2.xlsx
- Then merge ke summary sheet
```

### Q5: Apa jika feature tidak fungsional total?
**A**: 
Mark sebagai FAIL dengan note:
```
"Feature tidak functional - [deskripsi singkat]"
Severity: CRITICAL
Block further testing sampai fix
```

### Q6: Bagaimana dengan test data cleanup?
**A**: 
Jangan delete test data sampai selesai testing.
Untuk phase berikutnya:
```bash
php artisan migrate:fresh --seed  # Fresh start
```

### Q7: File mana yang harus di-deliver?
**A**: 
Minimal:
```
✅ CSV export (testing-report.csv)
✅ Bug list (BUGS_FOUND.md)
✅ Summary report (FINAL_TESTING_REPORT.md)

Optional:
- HTML report (untuk presentation)
- Screenshots folder
- Video recording (jika ada issue kompleks)
```

---

## Testing Quick Start Checklist

Copy-paste ini ke todo/checklist Anda:

```
🧪 TESTING EXECUTION CHECKLIST

PERSIAPAN (30 min):
[ ] Reset database fresh
[ ] Setup test users (4 akun)
[ ] Verify data master (10+ items)
[ ] Siapkan screenshot tool
[ ] Input nama tester

TESTING CUSTOMER (60 min):
[ ] C-01: Register Account
[ ] C-02: Login
[ ] C-03: Browse Products
[ ] C-04: Search Products
[ ] C-05: View Product Details
[ ] C-06: Add to Cart
[ ] C-07: View Cart
[ ] C-08: Update Cart Quantity
[ ] C-09: Remove from Cart
[ ] C-10: Checkout
[ ] C-11: Process Payment
[ ] C-12: View Orders

TESTING CASHIER (65 min):
[ ] K-01: Login
[ ] K-02: Create Transaction
[ ] K-03: Add Items (POS)
[ ] K-04: Check Stock
[ ] K-05: Update Item Quantity
[ ] K-06: Remove Item from Cart
[ ] K-07: Select Payment Method
[ ] K-08: Complete Transaction
[ ] K-09: Print Receipt
[ ] K-10: View Transactions
[ ] K-11: Process Online Orders
[ ] K-12: Process Marketplace Orders
[ ] K-13: Logout

TESTING ADMIN (55 min):
[ ] A-01: Login
[ ] A-02: Create Item
[ ] A-03: Edit Item
[ ] A-04: Delete Item
[ ] A-05: Upload Item Image
[ ] A-06: Manage Categories
[ ] A-07: Manage Suppliers
[ ] A-08: Manage Customers
[ ] A-09: View Sales Reports
[ ] A-10: Filter Reports
[ ] A-11: Export Reports

TESTING SUPERVISOR (80 min):
[ ] S-01: Login
[ ] S-02: Manage Users
[ ] S-03: Assign Roles
[ ] S-04: Create PR
[ ] S-05: Approve PR
[ ] S-06: Reject PR
[ ] S-07: Create PO
[ ] S-08: Edit PO Prices
[ ] S-09: Mark PO as Sent
[ ] S-10: Create Goods Receipt
[ ] S-11: Create Invoice
[ ] S-12: Mark Invoice Paid
[ ] S-13: View Stock Movement
[ ] S-14: View Procurement Dashboard
[ ] S-15: View Reports
[ ] S-16: Logout

DOKUMENTASI & REPORTING (60 min):
[ ] Document bugs ditemukan
[ ] Export hasil testing
[ ] Calculate pass rate
[ ] Write summary
[ ] Deliver ke stakeholder
```

---

## Contact & Support

Jika ada pertanyaan tentang testing plan:
- Lihat `TESTING_PLAN_BLACKBOX.md` untuk detail test cases
- Lihat `AKTOR_DAN_FITUR_LENGKAP.md` untuk fitur lengkap per aktor
- Lihat `NARASI_USECASE_DIAGRAM.md` untuk workflow lengkap

---

**Status**: Ready to Execute ✅
**Last Updated**: 2026-02-05
**Total Test Cases**: 52
**Estimated Duration**: 5-6 hours (1 tester)
