# TESTING PLAN - BLACKBOX TESTING
## Sistem POS Terintegrasi dengan Marketplace & Procurement

---

## 📋 DAFTAR FITUR YANG AKAN DI-TEST

Testing blackbox fokus pada **fitur fungsional utama** dari setiap aktor yang merupakan **critical path** sistem.

---

## 1️⃣ TESTING CUSTOMER (Pelanggan Marketplace)

### PRIORITAS: HIGH
**Jumlah Test Cases**: 12

| No | Fitur | Deskripsi | Test Type | Priority |
|----|-------|-----------|-----------|----------|
| C-01 | Register Account | Customer dapat mendaftar akun baru | Functional | HIGH |
| C-02 | Login | Customer dapat login dengan email/username | Functional | HIGH |
| C-03 | Browse Products | Customer dapat melihat daftar produk | Functional | HIGH |
| C-04 | Search Products | Customer dapat mencari produk | Functional | HIGH |
| C-05 | View Product Details | Customer dapat melihat detail produk | Functional | HIGH |
| C-06 | Add to Cart | Customer dapat menambah item ke keranjang | Functional | HIGH |
| C-07 | View Cart | Customer dapat melihat isi keranjang | Functional | MEDIUM |
| C-08 | Update Cart Quantity | Customer dapat mengubah jumlah item di cart | Functional | MEDIUM |
| C-09 | Remove from Cart | Customer dapat menghapus item dari cart | Functional | MEDIUM |
| C-10 | Checkout | Customer dapat lanjut ke pembayaran | Functional | HIGH |
| C-11 | Process Payment | Customer dapat melakukan pembayaran | Functional | HIGH |
| C-12 | View Orders | Customer dapat melihat riwayat pesanan | Functional | MEDIUM |

---

## 2️⃣ TESTING CASHIER (Kasir/POS Staff)

### PRIORITAS: HIGH
**Jumlah Test Cases**: 13

| No | Fitur | Deskripsi | Test Type | Priority |
|----|-------|-----------|-----------|----------|
| K-01 | Login | Cashier dapat login ke sistem | Functional | HIGH |
| K-02 | Create Transaction | Cashier dapat membuat transaksi baru | Functional | HIGH |
| K-03 | Add Items (POS) | Cashier dapat menambah item via barcode/code | Functional | HIGH |
| K-04 | Check Stock | Sistem cek stock saat add item | Functional | HIGH |
| K-05 | Update Item Quantity | Cashier dapat mengubah jumlah item di cart | Functional | HIGH |
| K-06 | Remove Item from Cart | Cashier dapat menghapus item dari cart | Functional | MEDIUM |
| K-07 | Select Payment Method | Cashier dapat memilih metode pembayaran | Functional | HIGH |
| K-08 | Complete Transaction | Cashier dapat menyelesaikan transaksi | Functional | HIGH |
| K-09 | Print Receipt | Sistem dapat print struk transaksi | Functional | HIGH |
| K-10 | View Transactions | Cashier dapat lihat riwayat transaksi | Functional | MEDIUM |
| K-11 | Process Online Orders | Cashier dapat proses pesanan online | Functional | HIGH |
| K-12 | Process Marketplace Orders | Cashier dapat proses pesanan marketplace | Functional | HIGH |
| K-13 | Logout | Cashier dapat logout dari sistem | Functional | MEDIUM |

---

## 3️⃣ TESTING ADMIN (Administrator)

### PRIORITAS: MEDIUM
**Jumlah Test Cases**: 11

| No | Fitur | Deskripsi | Test Type | Priority |
|----|-------|-----------|-----------|----------|
| A-01 | Login | Admin dapat login ke sistem | Functional | HIGH |
| A-02 | Create Item | Admin dapat membuat produk baru | Functional | HIGH |
| A-03 | Edit Item | Admin dapat mengubah data produk | Functional | HIGH |
| A-04 | Delete Item | Admin dapat menghapus produk | Functional | HIGH |
| A-05 | Upload Item Image | Admin dapat upload gambar produk | Functional | MEDIUM |
| A-06 | Manage Categories | Admin dapat CRUD kategori | Functional | MEDIUM |
| A-07 | Manage Suppliers | Admin dapat CRUD supplier | Functional | MEDIUM |
| A-08 | Manage Customers | Admin dapat CRUD customer | Functional | MEDIUM |
| A-09 | View Sales Reports | Admin dapat melihat laporan penjualan | Functional | MEDIUM |
| A-10 | Filter Reports | Admin dapat filter laporan | Functional | MEDIUM |
| A-11 | Export Reports | Admin dapat export laporan | Functional | LOW |

---

## 4️⃣ TESTING SUPERVISOR (Pimpinan)

### PRIORITAS: HIGH
**Jumlah Test Cases**: 16

| No | Fitur | Deskripsi | Test Type | Priority |
|----|-------|-----------|-----------|----------|
| S-01 | Login | Supervisor dapat login ke sistem | Functional | HIGH |
| S-02 | Manage Users | Supervisor dapat CRUD user | Functional | HIGH |
| S-03 | Assign Roles | Supervisor dapat assign role ke user | Functional | HIGH |
| S-04 | Create PR | Supervisor dapat membuat Purchase Request | Functional | HIGH |
| S-05 | Approve PR | Supervisor dapat approve PR | Functional | HIGH |
| S-06 | Reject PR | Supervisor dapat reject PR | Functional | HIGH |
| S-07 | Create PO | Supervisor dapat membuat Purchase Order | Functional | HIGH |
| S-08 | Edit PO Prices | Supervisor dapat edit harga di PO | Functional | HIGH |
| S-09 | Mark PO as Sent | Supervisor dapat mark PO sudah dikirim | Functional | HIGH |
| S-10 | Create Goods Receipt | Supervisor dapat membuat GR | Functional | HIGH |
| S-11 | Create Invoice | Supervisor dapat membuat Invoice | Functional | HIGH |
| S-12 | Mark Invoice Paid | Supervisor dapat mark Invoice paid | Functional | HIGH |
| S-13 | View Stock Movement | Supervisor dapat lihat analisis stok | Functional | MEDIUM |
| S-14 | View Procurement Dashboard | Supervisor dapat lihat dashboard procurement | Functional | MEDIUM |
| S-15 | View Reports | Supervisor dapat lihat laporan lengkap | Functional | MEDIUM |
| S-16 | Logout | Supervisor dapat logout | Functional | LOW |

---

## 📊 RINGKASAN TEST CASES

| Aktor | Jumlah Test Cases | Priority | Focus Area |
|-------|-------------------|----------|-----------|
| Customer | 12 | HIGH | Marketplace & Shopping |
| Cashier | 13 | HIGH | POS & Online Orders |
| Admin | 11 | MEDIUM | Master Data Management |
| Supervisor | 16 | HIGH | Procurement & Full Control |
| **TOTAL** | **52** | - | - |

---

## 🎯 CRITICAL PATH UNTUK TESTING

### Path 1: Customer Marketplace Journey
```
Register → Login → Browse → Search → View Details → 
Add to Cart → Checkout → Payment → View Order
```
**Test Cases**: C-01 to C-12
**Priority**: CRITICAL

### Path 2: POS Counter Transaction
```
Login → Create Transaction → Add Items → Check Stock → 
Update Qty → Payment → Print Receipt → Complete
```
**Test Cases**: K-01 to K-10
**Priority**: CRITICAL

### Path 3: Procurement Workflow
```
Create PR → Approve PR → Create PO → Mark Sent → 
Create GR → Create Invoice → Mark Paid
```
**Test Cases**: S-04 to S-12
**Priority**: CRITICAL

### Path 4: Master Data Management
```
Login → Create Item → Edit Item → Upload Image → 
Create Category → Create Supplier
```
**Test Cases**: A-02 to A-07, S-02 to S-03
**Priority**: HIGH

---

## 📝 TEST CASE TEMPLATE

```
TEST CASE ID: [ID]
TEST CASE NAME: [Nama]
AKTOR: [Aktor]
PRIORITY: [HIGH/MEDIUM/LOW]

PRECONDITIONS:
  - [Kondisi awal]
  - [Kondisi awal]

TEST STEPS:
  1. [Step 1]
  2. [Step 2]
  3. [Step 3]
  ... (n steps)

EXPECTED RESULT:
  - [Expected outcome]
  - [Data validation]

ACTUAL RESULT:
  - [ ] Pass
  - [ ] Fail
  - [ ] Blocked

NOTES:
  [Notes jika ada]

STATUS: [ ] VALID [ ] INVALID [ ] PENDING
```

---

## ✅ TESTING SCOPE

### IN SCOPE (Yang di-test):
- ✅ User authentication & authorization
- ✅ CRUD operations untuk master data
- ✅ Transaksi penjualan (POS & Marketplace)
- ✅ Workflow procurement
- ✅ Stock management
- ✅ Payment processing
- ✅ Order management
- ✅ Reporting & export

### OUT OF SCOPE (Yang TIDAK di-test):
- ❌ UI/UX testing (akan testing functionality)
- ❌ Performance testing
- ❌ Load testing
- ❌ Security penetration testing
- ❌ Database optimization
- ❌ Email sending (assumed working)
- ❌ Payment gateway integration (mocking saja)

---

## 🔄 TESTING APPROACH

### Phase 1: Basic Functionality (Week 1)
- Test authentication (login/register)
- Test basic CRUD operations
- Test core transactions

### Phase 2: Workflow Testing (Week 2)
- Test complete workflows
- Test approval processes
- Test stock management

### Phase 3: Integration Testing (Week 3)
- Test data consistency
- Test cross-aktor interactions
- Test reporting

### Phase 4: Edge Cases & Validation (Week 4)
- Test error handling
- Test validation rules
- Test permission denied scenarios

---

## 📋 TESTING CHECKLIST

### Pre-Testing:
- [ ] Database fresh (seeding data)
- [ ] All features accessible
- [ ] Test accounts created
  - [ ] Customer account
  - [ ] Cashier account
  - [ ] Admin account
  - [ ] Supervisor account
- [ ] Test data prepared
  - [ ] Products
  - [ ] Categories
  - [ ] Suppliers
  - [ ] Payment methods

### During Testing:
- [ ] Execute test cases per schedule
- [ ] Record results
- [ ] Document bugs/issues
- [ ] Take screenshots jika ada issue
- [ ] Update checklist status

### Post-Testing:
- [ ] Compile test report
- [ ] Analyze pass rate
- [ ] Identify blockers
- [ ] Document recommendations

---

## 🐛 BUG REPORT TEMPLATE

```
BUG ID: [ID]
SEVERITY: [CRITICAL/HIGH/MEDIUM/LOW]
STATUS: [OPEN/CLOSED/RESOLVED]

TITLE: [Deskripsi singkat bug]

DESCRIPTION:
  [Deskripsi lengkap]

STEPS TO REPRODUCE:
  1. [Step 1]
  2. [Step 2]
  3. [Step 3]

EXPECTED vs ACTUAL:
  Expected: [Seharusnya apa]
  Actual: [Yang terjadi]

ENVIRONMENT:
  - Browser: [Browser & version]
  - OS: [Operating system]
  - Date: [Tanggal]

ATTACHMENTS:
  - Screenshot: [Link/file]
  - Error log: [Link/file]
```

---

## 📊 TESTING REPORT TEMPLATE

```
PROJECT: Sistem POS Terintegrasi
DATE: [Date]
TESTED BY: [Nama tester]

SUMMARY:
  - Total Test Cases: [Jumlah]
  - Passed: [Jumlah] ([%])
  - Failed: [Jumlah] ([%])
  - Blocked: [Jumlah] ([%])
  - Pass Rate: [%]

CRITICAL ISSUES: [Jumlah]
HIGH ISSUES: [Jumlah]
MEDIUM ISSUES: [Jumlah]
LOW ISSUES: [Jumlah]

FEATURES TESTED:
  ✓ [Feature 1]
  ✓ [Feature 2]
  ✗ [Feature 3] - Issue: [Deskripsi]
  
RECOMMENDATIONS:
  1. [Recommendation]
  2. [Recommendation]

CONCLUSION: [Conclusion]
```

---

## 🎯 SUCCESS CRITERIA

Testing dianggap **BERHASIL** jika:
- ✅ Pass rate ≥ 90%
- ✅ NO critical issues
- ✅ Semua critical paths berjalan
- ✅ Approval workflows berfungsi
- ✅ Stock management akurat
- ✅ Data consistency terjaga
- ✅ Payment processing berhasil

---

**Testing Plan Created**: February 5, 2026  
**Total Test Cases**: 52  
**Estimated Duration**: 4 weeks  
**Status**: Ready for Testing
