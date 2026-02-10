# DOKUMENTASI SISTEM POS TERINTEGRASI
## Index & Panduan Penggunaan

---

## 📋 OVERVIEW SISTEM

Sistem POS Terintegrasi dengan Marketplace & Procurement adalah platform komprehensif untuk mengelola:
- **Penjualan Online** (Marketplace)
- **Penjualan Offline** (POS Counter)
- **Manajemen Inventory** (Stok & Batch Tracking)
- **Procurement Workflow** (PR → PO → GR → Invoice)
- **Reporting & Analytics**
- **User Management** (Role-based access)

---

## 📁 DOKUMENTASI LENGKAP

### 1. **RINGKASAN_FITUR.md** ⭐ START HERE
**Tujuan**: Quick reference untuk semua fitur sistem  
**Isi**:
- Ringkasan singkat setiap aktor (6 roles)
- Fitur per subsistem
- Workflow diagrams
- Keamanan & access control
- Tech stack info

**Penggunaan**: 
- Baca pertama kali untuk understanding struktur sistem
- Reference cepat untuk stats & key features

---

### 2. **AKTOR_DAN_FITUR_LENGKAP.md**
**Tujuan**: Dokumentasi detail fitur setiap aktor  
**Isi**:
- **6 Aktor** dengan deskripsi & fitur lengkap:
  - Customer (16 fitur)
  - Cashier (19 fitur)
  - Admin (14 fitur)
  - Supervisor (60+ fitur)
  - Supplier (5 fitur)
  - Owner (4 fitur)
- Workflow procurement detail
- Workflow POS & marketplace
- Notes untuk usecase diagram

**Penggunaan**: 
- Reference detail untuk setiap role
- Gunakan saat membuat dokumentasi skripsi
- Base untuk usecase diagram

---

### 3. **NARASI_USECASE_DIAGRAM.md**
**Tujuan**: Narasi lengkap untuk setiap use case dengan struktur formal  
**Isi**:
- **13 Use Case Narasi** dengan detail:
  - UC-C1 sampai UC-C6 (Customer - 6 use cases)
  - UC-K1 sampai UC-K4 (Cashier - 4 use cases)
  - UC-A1 sampai UC-A5 (Admin - 5 use cases)
  - UC-S1 sampai UC-S15 (Supervisor - 15 use cases)
  - UC-S1 sampai UC-S5 (Supplier - 5 use cases)
  - UC-O1 sampai UC-O3 (Owner - 3 use cases)
- Interaction matrix
- Primary workflows
- System boundaries
- Use case diagram structure

**Penggunaan**: 
- Gunakan sebagai narasi formal untuk laporan
- Extract untuk membuat use case descriptions
- Reference untuk interaction diagrams

---

### 4. **USECASE_DIAGRAM.puml** 📊
**Tujuan**: PlantUML source code untuk usecase diagram  
**Isi**:
- Diagram struktur lengkap dengan 6 actors
- 50+ use cases dengan relationship
- 6 subsystems (Marketplace, POS, Inventory, Procurement, User Mgmt, Reporting)
- Aktor interactions
- Dependency relationships

**Cara Menggunakan**:
```
1. Copy seluruh code dari file
2. Buka PlantUML editor online: https://www.plantuml.com/plantuml/uml/
   (atau VS Code dengan PlantUML extension)
3. Paste code
4. Generate diagram
5. Export sebagai PNG/SVG/PDF
```

**Atau di VS Code**:
```
1. Install PlantUML extension
2. Buka file USECASE_DIAGRAM.puml
3. Right-click → "PlantUML: Preview"
4. View generated diagram
5. Export via PlantUML menu
```

---

### 5. **DETAILED_USE_CASES.md**
**Tujuan**: Deskripsi formal setiap use case dengan standard template  
**Isi**:
- **30+ Use Cases** dengan format formal:
  - Use Case ID
  - Use Case Name
  - Actor(s)
  - Preconditions
  - Main Flow
  - Alternative Flow
  - Postconditions
  - Business Rules

**Use Cases Covered**:
- UC-MP-01 sampai UC-MP-13 (Marketplace - 13 use cases)
- UC-POS-01 sampai UC-POS-10 (POS System - 10 use cases)
- UC-PROC-01 sampai UC-PROC-13 (Procurement - 13 use cases)
- UC-USER-01 sampai UC-USER-03 (User Management - 3 use cases)
- UC-REP-01, UC-REP-02 (Reporting - 2 use cases)

**Penggunaan**: 
- Gunakan untuk penjelasan detail di laporan
- Reference untuk sequence diagram creation
- Dokumentasi teknis lengkap

---

## 🎯 PANDUAN PENGGUNAAN BERDASARKAN KEBUTUHAN

### Jika Anda Ingin Membuat Skripsi:

1. **Baca RINGKASAN_FITUR.md** (15 min)
   - Pahami struktur & aktor sistem

2. **Baca AKTOR_DAN_FITUR_LENGKAP.md** (30 min)
   - Pahami detail fitur setiap role

3. **Lihat USECASE_DIAGRAM.puml** (10 min)
   - Visualisasi struktur use cases

4. **Gunakan NARASI_USECASE_DIAGRAM.md** (1 hour)
   - Tuliskan narasi untuk laporan Anda

5. **Referensi DETAILED_USE_CASES.md**
   - Untuk penjelasan detail per use case

### Jika Anda Ingin Membuat Presentation:

1. **RINGKASAN_FITUR.md** - untuk overview
2. **USECASE_DIAGRAM.puml** - generate diagram sebagai visual aid
3. **NARASI_USECASE_DIAGRAM.md** - untuk explanation

### Jika Anda Ingin Melanjutkan Development:

1. **AKTOR_DAN_FITUR_LENGKAP.md** - pahami fitur yang sudah ada
2. **DETAILED_USE_CASES.md** - pahami flow & business rules
3. Lihat file di `routes/web.php` & `app/Http/Controllers/` untuk implementasi

---

## 🔍 AKTOR & JUMLAH FITUR

| Aktor | Fitur | Priority | Status |
|-------|-------|----------|--------|
| Customer | 16 | High | ✅ Implemented |
| Cashier | 19 | High | ✅ Implemented |
| Admin | 14 | Medium | ✅ Implemented |
| Supervisor | 60+ | High | ✅ Implemented |
| Supplier | 5 | Low-Medium | 📋 Partial |
| Owner | 4 | Low | ❌ Optional |

**Total Use Cases**: 50+  
**Total Fitur**: 100+  
**Subsystem**: 6

---

## 📊 STRUKTUR SUBSYSTEM

```
SISTEM POS TERINTEGRASI
├── MARKETPLACE SYSTEM (13 use cases)
│   ├── Customer browsing & shopping
│   ├── Cart management
│   ├── Checkout & payment
│   └── Order tracking
│
├── POS SYSTEM (10 use cases)
│   ├── Transaction creation
│   ├── Item management
│   ├── Payment processing
│   └── Receipt printing
│
├── ONLINE ORDERS PROCESSING (4 use cases)
│   ├── View online orders
│   ├── Process payments
│   └── Marketplace orders
│
├── INVENTORY MANAGEMENT (11 use cases)
│   ├── Product CRUD
│   ├── Category management
│   ├── Supplier management
│   ├── Batch tracking
│   └── Stock analysis
│
├── PROCUREMENT WORKFLOW (13 use cases)
│   ├── Purchase Request (PR)
│   ├── Purchase Order (PO)
│   ├── Goods Receipt (GR)
│   ├── Inventory Record
│   └── Invoice management
│
├── USER MANAGEMENT (3 use cases)
│   ├── User CRUD
│   ├── Role assignment
│   └── Password reset
│
└── REPORTING & ANALYTICS (2 use cases)
    ├── Sales reports
    └── Stock analysis
```

---

## 🔐 ROLE & ACCESS LEVEL

```
Public Access:
  ├── Register
  ├── Login
  └── Browse Marketplace (no login required)

Customer (After Login):
  ├── Marketplace (full)
  ├── My Cart
  ├── My Orders
  └── My Profile

Cashier (Internal Staff):
  ├── POS System (full)
  ├── Inventory (view only)
  ├── Online Orders (process)
  └── Marketplace Orders (process)

Admin:
  ├── Inventory (CRUD)
  ├── Customer (CRUD)
  ├── Reports (view/export)
  └── Master data management

Supervisor (Management):
  ├── Full system access
  ├── User management
  ├── Procurement workflow (full)
  ├── Stock analysis
  ├── All reports
  └── Configuration

Supplier (External):
  ├── Receive PO
  ├── Deliver goods
  ├── Submit invoice
  └── (No system login)
```

---

## 🚀 WORKFLOW UTAMA

### Workflow 1: Marketplace Customer Journey
```
Browse → Search → View Details → Add Cart → Checkout → Pay → Order Placed
```

### Workflow 2: POS Counter Transaction
```
New Transaction → Add Items → Check Stock → Payment → Print Receipt → Complete
```

### Workflow 3: Complete Procurement Cycle
```
PR Create → PR Approve → PO Create → PO Send → GR Create → Inventory Record → 
Invoice Create → Invoice Pay → Complete
```

### Workflow 4: Stock Reorder via Analysis
```
View Stock Movement → Identify Needs → Create PR → Approve → Create PO → 
Monitor Delivery → Receive & Record → Complete
```

---

## 📝 TEKNOLOGI & ARSITEKTUR

**Backend**: Laravel 11 (PHP)  
**Database**: MySQL/PostgreSQL  
**Frontend**: Blade + Tailwind CSS + jQuery  
**Documentation**: PlantUML + Markdown  

**Key Directories**:
- `routes/web.php` - Route definitions
- `app/Http/Controllers/` - Business logic (30+ controllers)
- `app/Models/` - Data models (15+ models)
- `app/Http/Middleware/` - Access control
- `resources/views/` - UI templates

---

## ✅ CHECKLIST UNTUK DOKUMENTASI SKRIPSI

- [ ] Read RINGKASAN_FITUR.md
- [ ] Read AKTOR_DAN_FITUR_LENGKAP.md
- [ ] Generate USECASE_DIAGRAM.puml & include dalam laporan
- [ ] Copy NARASI_USECASE_DIAGRAM.md content ke chapter Usecase
- [ ] Copy relevant DETAILED_USE_CASES.md untuk detail per use case
- [ ] Create sequence diagrams untuk key workflows
- [ ] Create ER diagram untuk database (referensi: `app/Models/`)
- [ ] Create activity diagrams untuk procurements & workflows
- [ ] Document system boundaries & scope
- [ ] Document assumptions & constraints

---

## 📞 FILE REFERENCES

Semua dokumentasi ini refer ke file actual di repository:
- `routes/web.php` - Definisi routes & access control
- `app/Http/Controllers/TransactionController.php` - POS logic
- `app/Http/Controllers/MarketplaceController.php` - Marketplace logic
- `app/Http/Controllers/ProcurementController.php` - Procurement orchestration
- `app/Http/Controllers/PurchaseRequestController.php` - PR management
- `app/Http/Controllers/NewPurchaseOrderController.php` - PO management
- `app/Http/Controllers/GoodsReceiptController.php` - GR management
- `app/Http/Controllers/InvoiceController.php` - Invoice management
- `app/Http/Controllers/StockMovementController.php` - Stock analysis
- `app/Models/User.php` - User model dengan roles
- `app/Models/Transaction.php` - Transaction model
- `app/Models/PurchaseRequest.php` - PR model
- `app/Models/Item.php` - Product/Item model

---

## 📌 CATATAN PENTING

1. **Supervisor adalah role paling powerful** dengan akses ke semua subsystem
2. **Procurement workflow lengthy** - PR → PO → GR → Invoice (4 dokumen)
3. **Stock management real-time** - checked saat add to cart (POS & Marketplace)
4. **Multi-channel sales** - POS (offline) + Marketplace (online) dalam satu inventory
5. **Role-based access** diimplementasikan via Middleware (IsAdmin, IsSupervisor, etc)
6. **Payment methods configurable** - dapat disesuaikan per toko

---

## 🎓 UNTUK PENULISAN LAPORAN

### Struktur BAB yang Bisa Digunakan:

**BAB 3: ANALYSIS & DESIGN**
- 3.1 System Overview → Gunakan RINGKASAN_FITUR.md
- 3.2 Actor & Role → Gunakan AKTOR_DAN_FITUR_LENGKAP.md
- 3.3 Use Case Diagram → Gunakan USECASE_DIAGRAM.puml
- 3.4 Use Case Narration → Gunakan NARASI_USECASE_DIAGRAM.md
- 3.5 Detail Use Cases → Gunakan DETAILED_USE_CASES.md
- 3.6 Workflow Diagrams → Buat berdasarkan workflow info di sini
- 3.7 Database Design → Reference app/Models/ files
- 3.8 System Architecture → Reference tech stack & directories

---

## 📊 METRICS & STATISTICS

**Codebase Size**:
- 30+ Controllers
- 15+ Models
- 100+ Routes
- 6 Subsystems
- 50+ Use Cases
- 100+ Features

**Development Approach**:
- RESTful API design
- MVC Architecture
- Role-based Access Control (RBAC)
- Database transaction integrity
- Document-based procurement workflow

---

## 🔄 NEXT STEPS

Setelah membaca dokumentasi ini, langkah selanjutnya:

1. **Generate Diagram**
   - Use USECASE_DIAGRAM.puml untuk visual

2. **Create Supporting Diagrams**
   - Sequence diagrams untuk workflows
   - Activity diagrams untuk procurement
   - ER diagram untuk database
   - State diagrams untuk order status

3. **Document Implementation**
   - Database schema
   - API endpoints
   - Configuration

4. **User Manual** (Optional)
   - Per-role user guide
   - Feature walkthrough
   - Troubleshooting

---

**Dokumentasi Created**: February 5, 2026  
**System**: Laravel POS Terintegrasi v1.0  
**Status**: Complete & Ready for Thesis Documentation
