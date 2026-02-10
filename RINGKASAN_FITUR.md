# SUMMARY: AKTOR & FITUR SISTEM POS TERINTEGRASI
## Quick Reference untuk Usecase Diagram

---

## RINGKASAN SINGKAT SETIAP AKTOR

### 1️⃣ CUSTOMER (Pelanggan Marketplace)
**Akses**: Publik (self-register)  
**Tujuan Utama**: Berbelanja produk online melalui marketplace  
**Fitur Utama**:
- Register/Login/Logout
- Browse & Search Produk
- Manage Cart (Add/Update/Remove)
- Checkout & Payment Online
- Track Order Status

**Jumlah Fitur**: 16  
**Priority**: High

---

### 2️⃣ CASHIER (Staf POS)
**Akses**: Dibuat oleh Supervisor  
**Tujuan Utama**: Mengelola transaksi penjualan (offline POS + online orders)  
**Fitur Utama**:
- Create/Manage POS Transaction
- Add Items & Check Stock Real-time
- Complete Transaction & Print Receipt
- Process Online Order Payments
- Process Marketplace Order Payments

**Jumlah Fitur**: 19  
**Priority**: High

---

### 3️⃣ ADMIN (Administrator)
**Akses**: Dibuat oleh Supervisor  
**Tujuan Utama**: Manajemen master data inventory dan customer  
**Fitur Utama**:
- CRUD Products (Item Management)
- CRUD Categories
- CRUD Suppliers
- CRUD Customers
- View & Export Sales Reports

**Jumlah Fitur**: 14  
**Priority**: Medium

---

### 4️⃣ SUPERVISOR (Pimpinan/Manager)
**Akses**: Role tertinggi  
**Tujuan Utama**: Kontrol penuh operasional, management user, procurement workflow  
**Fitur Utama**:
- Manage Users (CRUD + Roles)
- Master Data Inventory (Items, Categories, Suppliers)
- Batch & Expiry Tracking
- Stock Movement Analysis
- Full Procurement Workflow (PR → PO → GR → Invoice)
- Payment Method Management
- Comprehensive Reporting

**Jumlah Fitur**: 60+  
**Priority**: High

---

### 5️⃣ SUPPLIER (Vendor/Penyedia)
**Akses**: Eksternal (tidak login ke sistem)  
**Tujuan Utama**: Menyediakan produk dan merespons order  
**Fitur Utama** (dalam context sistem):
- Provide Product List
- Receive Purchase Orders
- Deliver Goods
- Submit Invoice

**Jumlah Fitur**: 5  
**Priority**: Low-Medium

---

### 6️⃣ OWNER (Pemilik Bisnis)
**Akses**: Opsional/Potential  
**Tujuan Utama**: Monitor performa bisnis strategis  
**Fitur Utama**:
- View Dashboard & KPIs
- Sales Analytics
- Financial Reports
- Performance Monitoring

**Jumlah Fitur**: 4  
**Priority**: Low

---

## FITUR PER SUBSISTEM

### 📦 MARKETPLACE SUBSYSTEM
**Actors**: Customer, Cashier, Admin, Supervisor
- Browse Products
- Manage Cart
- Checkout & Payment
- Order Management
- Process Online Payments

### 🏪 POS SUBSYSTEM
**Actors**: Cashier, Supervisor
- Create Transaction
- Add Items (with Stock Check)
- Manage Cart Items
- Complete Transaction
- Print Receipt
- View Transactions

### 📚 INVENTORY SUBSYSTEM
**Actors**: Admin, Supervisor, Cashier (View)
- Product Management
- Category Management
- Supplier Management
- Batch & Expiry Tracking
- Stock Movement Analysis
- Stock Visibility

### 🔄 PROCUREMENT SUBSYSTEM
**Actors**: Supervisor (Primary), Admin (View)
- Purchase Request (Create, View, Approve/Reject)
- Purchase Order (Create, View, Edit, Send)
- Goods Receipt (Create, View, Download)
- Inventory Record (Create, View)
- Invoice Management (Create, View, Pay)
- Workflow Approval

### 👥 USER MANAGEMENT SUBSYSTEM
**Actors**: Supervisor (Primary)
- User CRUD
- Role Assignment
- Password Management
- User Activation/Deactivation
- Position Management

### 📊 REPORTING SUBSYSTEM
**Actors**: Supervisor, Admin, Owner
- Sales Reports
- Purchase Reports
- Stock Movement Reports
- Transaction History
- Financial Analytics
- Export to Excel/PDF

---

## WORKFLOW PROCUREMENT DETAIL

```
┌─────────────────┐
│  Create PR      │  Supervisor membuat Purchase Request
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Approve/Reject │  Supervisor review dan approve PR
│      PR         │  (atau reject dengan reason)
└────────┬────────┘
         │
         ▼ (jika approved)
┌─────────────────┐
│  Create PO      │  Supervisor membuat PO dari PR
└────────┬────────┘
         │
         ├──→ Edit Prices (opsional)
         │
         ├──→ Confirm Prices
         │
         ▼
┌─────────────────┐
│  Send PO to     │  Mark PO as "sent"
│  Supplier       │  Generate PDF untuk supplier
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Goods Arrive   │  Supplier delivers goods
│  from Supplier  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Create GR      │  Supervisor membuat Goods Receipt
│  (Barang Masuk) │  Catat item yang diterima
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Inventory      │  Supervisor record items ke stok
│  Record         │  Update available stock
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Receive        │  Supplier kirim invoice
│  Invoice        │  Upload invoice ke sistem
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Mark as Paid   │  Supervisor konfirmasi pembayaran
│                 │  Invoice ditandai paid
└─────────────────┘
```

---

## WORKFLOW POS TRANSACTION

```
COUNTER / POS SYSTEM:

Cashier opens POS
    │
    ▼
Create New Transaction (Get Invoice Number)
    │
    ▼
    ├→ Scan/Enter Item Code
    │
    ├→ Check Stock (Real-time validation)
    │   │
    │   ├→ Stock Available? YES → Add to Cart
    │   │
    │   └→ Stock Available? NO → Reject / Suggest Alternative
    │
    ├→ Adjust Quantity (Update/Remove from Cart)
    │
    ├→ [Loop until customer ready to pay]
    │
    ▼
Select Payment Method
    │
    ├→ Cash
    ├→ Debit/Credit Card
    ├→ Digital Wallet
    └→ Other Methods
    │
    ▼
Process Payment
    │
    ▼
Print Receipt (Struk)
    │
    ▼
Complete Transaction
    │
    ▼
Transaction Recorded in Database
```

---

## WORKFLOW MARKETPLACE (CUSTOMER PERSPECTIVE)

```
Customer Journey:

1. Browse
   ├→ Visit Marketplace
   ├→ See Product List
   └→ View Product Details

2. Shop
   ├→ Add to Cart
   ├→ View Cart
   ├→ Update Quantity
   └→ Remove Items

3. Checkout
   ├→ Proceed to Checkout
   ├→ Confirm Delivery Address
   ├→ Select Payment Method
   └→ Process Payment

4. Order Tracking
   ├→ View My Orders
   ├→ See Order Details
   ├→ Track Status (Pending → Processing → Completed)
   └→ View Invoice
```

---

## KEAMANAN & ACCESS CONTROL

```
Public Access:
├─ /register (Public)
├─ /login (Public)
└─ /marketplace (Public - Browse only)

Customer Only (Authenticated):
├─ /marketplace/cart
├─ /marketplace/checkout
├─ /marketplace/orders
└─ /profile

Cashier (Auth + IsCashier):
├─ /transaction (POS System)
├─ /cart (POS Cart)
└─ /transaction/marketplace-orders

Admin (Auth + IsAdmin):
├─ /inventory/item
├─ /inventory/category
├─ /customer
└─ /report

Supervisor (Auth + IsSupervisor):
├─ /user (User Management)
├─ /procurement (Full workflow)
├─ /inventory (CRUD)
├─ /stock-movement (Analysis)
├─ /payment-method
├─ /absence
└─ /report (Complete access)

Admin or Supervisor (IsAdminOrSupervisor):
├─ /inventory (Category, Supplier, Item)
├─ /stock-movement
├─ /procurement
├─ /payment-method
├─ /absence
└─ /report
```

---

## FITUR UNGGULAN SISTEM

### ✅ Real-time Stock Management
- Cashier dapat check stock saat membuat transaksi
- Stok otomatis dikurangi saat transaksi selesai
- Alert untuk stok habis

### ✅ Multi-Channel Sales
- POS (Offline) di Counter
- Marketplace (Online)
- Integrated inventory untuk kedua channel

### ✅ Procurement Workflow Lengkap
- Dari PR sampai Invoice dalam satu sistem
- Approval workflow dengan role separation
- Document generation (PDF)

### ✅ Stock Movement Analysis
- Identify fast-moving & slow-moving products
- Help dengan reorder decision
- Data-driven inventory management

### ✅ Role-Based Access Control
- 5 Role utama (Customer, Cashier, Admin, Supervisor, Owner)
- Granular permission management
- Audit trail untuk compliance

### ✅ Comprehensive Reporting
- Sales reports dengan filter
- Purchase reports
- Stock analytics
- Export functionality

---

## STATISTIK SISTEM

| Metric | Value |
|--------|-------|
| Total Actors | 6 (atau 5 jika Owner opsional) |
| Total Fitur | 100+ |
| Core Subsystems | 6 |
| Database Models | 15+ |
| API Endpoints | 20+ |
| Reports Available | 10+ |

---

## DEVELOPMENT NOTES

### Tech Stack:
- **Backend**: Laravel 11
- **Database**: MySQL/PostgreSQL
- **Frontend**: Blade Templates + Tailwind CSS + jQuery
- **Documentation**: PlantUML for diagrams

### File-file Important:
- `routes/web.php` - Route definitions
- `app/Http/Controllers/` - Business logic
- `app/Models/` - Data models
- `app/Http/Middleware/` - Access control

### Database Design:
- Role-based authorization at middleware level
- Relationship models untuk complex workflows
- Transaction integrity untuk POS & Procurement

---

## NEXT STEPS

1. ✅ Create detailed Actor profiles (AKTOR_DAN_FITUR_LENGKAP.md)
2. ✅ Create use case narratives (NARASI_USECASE_DIAGRAM.md)
3. ✅ Create quick reference (This file - RINGKASAN_FITUR.md)
4. 📝 **TODO**: Create PlantUML usecase diagram code
5. 📝 **TODO**: Create sequence diagrams for key workflows
6. 📝 **TODO**: Create ER diagram untuk database
7. 📝 **TODO**: Create UI mockups/wireframes
