# VISUAL SUMMARY - AKTOR & FITUR SISTEM
## Quick Lookup Table & Visual Maps

---

## 🎭 AKTOR OVERVIEW

### AKTOR MATRIX

```
┌─────────────┬─────────────┬──────────────┬─────────────┬─────────────┬──────────────┐
│  AKTOR      │ AKSES       │ TOTAL FITUR  │ SUBSYSTEM   │ PRIORITY    │ MAIN ROLE    │
├─────────────┼─────────────┼──────────────┼─────────────┼─────────────┼──────────────┤
│ Customer    │ Publik      │ 16 fitur    │ Marketplace │ HIGH        │ Berbelanja   │
│             │ (Register)  │             │             │             │              │
├─────────────┼─────────────┼──────────────┼─────────────┼─────────────┼──────────────┤
│ Cashier     │ Internal    │ 19 fitur    │ POS +       │ HIGH        │ Transaksi   │
│             │ (Diatur)    │             │ Online Ord  │             │ & Penjualan │
├─────────────┼─────────────┼──────────────┼─────────────┼─────────────┼──────────────┤
│ Admin       │ Internal    │ 14 fitur    │ Inventory + │ MEDIUM      │ Master Data │
│             │ (Diatur)    │             │ Reporting   │             │ Management  │
├─────────────┼─────────────┼──────────────┼─────────────┼─────────────┼──────────────┤
│ Supervisor  │ Internal    │ 60+ fitur   │ ALL         │ HIGH        │ Full Control│
│             │ (Top Role)  │             │             │             │              │
├─────────────┼─────────────┼──────────────┼─────────────┼─────────────┼──────────────┤
│ Supplier    │ Eksternal   │ 5 fitur     │ Procurement │ LOW-MEDIUM  │ Vendor      │
│             │ (No Login)  │             │ (Receiving) │             │              │
├─────────────┼─────────────┼──────────────┼─────────────┼─────────────┼──────────────┤
│ Owner       │ Internal    │ 4 fitur     │ Analytics   │ LOW         │ Strategy    │
│             │ (Optional)  │             │             │             │              │
└─────────────┴─────────────┴──────────────┴─────────────┴─────────────┴──────────────┘
```

---

## 🎯 AKTOR & SUBSYSTEM ACCESS MAP

```
                     ┌─────────────────────────────────────────┐
                     │   MARKETPLACE SYSTEM                    │
                     │  Browse, Cart, Checkout, Orders         │
                     └─────────────────────────────────────────┘
                                    │
                    ┌───────────────┼───────────────┐
                    │               │               │
                    ▼               ▼               ▼
                CUSTOMER        CASHIER         SUPERVISOR
              (Full Access)  (Order Process)  (Full Admin)
              
              
                     ┌─────────────────────────────────────────┐
                     │     POS SYSTEM                          │
                     │  Transaction, Cart, Payment, Receipt    │
                     └─────────────────────────────────────────┘
                                    │
                    ┌───────────────┼───────────────┐
                    │               │               │
                    ▼               ▼               ▼
                  (None)         CASHIER         SUPERVISOR
                              (Full Access)    (Full Admin)
              
              
                     ┌─────────────────────────────────────────┐
                     │   INVENTORY MANAGEMENT                  │
                     │  Items, Categories, Suppliers, Batches  │
                     └─────────────────────────────────────────┘
                                    │
                    ┌───────────────┼───────────────┐
                    │               │               │
                    ▼               ▼               ▼
                  (None)           ADMIN         SUPERVISOR
                              (Full Access)    (Full Admin)
              
              
                     ┌─────────────────────────────────────────┐
                     │   PROCUREMENT WORKFLOW                  │
                     │  PR, PO, GR, Inventory Record, Invoice  │
                     └─────────────────────────────────────────┘
                                    │
                    ┌───────────────┼───────────────┐
                    │               │               │
                    ▼               ▼               ▼
                SUPPLIER       (Admin-view)    SUPERVISOR
              (Receive docs)    (Limited)      (Full Control)
              
              
                     ┌─────────────────────────────────────────┐
                     │   REPORTING & ANALYTICS                 │
                     │  Sales, Purchase, Stock Reports         │
                     └─────────────────────────────────────────┘
                                    │
                    ┌───────────────┼───────────────┬───────────┐
                    │               │               │           │
                    ▼               ▼               ▼           ▼
                  (None)          ADMIN        SUPERVISOR      OWNER
                              (View/Export) (Full Access)  (Analytics)
```

---

## 📊 FITUR BREAKDOWN PER AKTOR

### CUSTOMER (16 Fitur)
```
┌─ AUTHENTICATION (3)
│  ├─ Register
│  ├─ Login
│  └─ Logout
│
├─ MARKETPLACE (7)
│  ├─ Browse Products
│  ├─ Search/Filter
│  ├─ View Details
│  ├─ Add to Cart
│  ├─ View Cart
│  ├─ Update Quantity
│  └─ Remove from Cart
│
├─ CHECKOUT (3)
│  ├─ Clear Cart
│  ├─ Checkout
│  └─ Process Payment
│
└─ ORDERS (3)
   ├─ View Orders
   ├─ View Details
   └─ Track Status
```

### CASHIER (19 Fitur)
```
┌─ AUTHENTICATION (2)
│  ├─ Login
│  └─ Logout
│
├─ POS TRANSACTIONS (10)
│  ├─ Create Transaction
│  ├─ Add Items
│  ├─ Check Stock
│  ├─ Update Quantity
│  ├─ Remove Item
│  ├─ Complete Transaction
│  ├─ Select Payment
│  ├─ Print Receipt
│  ├─ View Transactions
│  └─ View Details
│
└─ ONLINE ORDERS (7)
   ├─ View Online Orders
   ├─ View Items
   ├─ Process Payment
   ├─ View Marketplace Orders
   ├─ View Marketplace Items
   ├─ Process Marketplace Payment
   └─ View Inventory Items
```

### ADMIN (14 Fitur)
```
├─ AUTHENTICATION (2)
│  ├─ Login
│  └─ Logout
│
├─ PRODUCT MANAGEMENT (5)
│  ├─ Manage Items (CRUD)
│  ├─ Set Pricing
│  ├─ Upload Images
│  ├─ Manage Categories
│  └─ Manage Suppliers
│
├─ CUSTOMER MANAGEMENT (2)
│  ├─ Manage Customers (CRUD)
│  └─ View/Edit Profile
│
└─ REPORTING (3)
   ├─ Sales Reports
   ├─ Filter & Export
   └─ View Details
```

### SUPERVISOR (60+ Fitur)
```
├─ USER MANAGEMENT (6)
│  ├─ Manage Users (CRUD)
│  ├─ Assign Roles
│  ├─ Activate/Deactivate
│  ├─ Reset Password
│  ├─ Login
│  └─ Logout
│
├─ INVENTORY MANAGEMENT (12)
│  ├─ Manage Items (CRUD)
│  ├─ Manage Categories
│  ├─ Manage Suppliers
│  ├─ Set Pricing
│  ├─ Upload Images
│  ├─ Manage Batches
│  ├─ Track Expiry
│  ├─ Stock Movement Analysis
│  ├─ Fast-Moving Items
│  ├─ Slow-Moving Items
│  ├─ View Stock Details
│  └─ Configure Settings
│
├─ PROCUREMENT (35+)
│  ├─ Purchase Request (6)
│  │  ├─ Create PR
│  │  ├─ View PR List
│  │  ├─ Approve PR
│  │  ├─ Reject PR
│  │  ├─ Generate PDF
│  │  └─ View Details
│  │
│  ├─ Purchase Order (6)
│  │  ├─ Create PO
│  │  ├─ View PO List
│  │  ├─ Edit Prices
│  │  ├─ Confirm Prices
│  │  ├─ Mark Sent
│  │  └─ Generate PDF
│  │
│  ├─ Goods Receipt (4)
│  │  ├─ Create GR
│  │  ├─ Record Items
│  │  ├─ View GR
│  │  └─ Download
│  │
│  ├─ Inventory Record (3)
│  │  ├─ Create Record
│  │  └─ View Records
│  │
│  └─ Invoice (6)
│     ├─ Create Invoice
│     ├─ View Invoice
│     ├─ Upload File
│     ├─ Mark Paid
│     ├─ Upload Proof
│     └─ Download
│
├─ SETTINGS & CONFIG (4)
│  ├─ Payment Methods
│  ├─ Absences
│  ├─ Stock Settings
│  └─ General Config
│
└─ REPORTING (5)
   ├─ Sales Reports
   ├─ Purchase Reports
   ├─ Stock Reports
   ├─ Procurement Dashboard
   └─ Performance Analytics
```

---

## 🔄 WORKFLOW VISUAL MAPS

### WORKFLOW 1: MARKETPLACE SHOPPING
```
START (Customer Not Logged In)
  │
  ├─→ [Guest Browse]
  │    └─→ Search/Filter Products
  │         └─→ View Details
  │
  └─→ [Add to Cart] ──→ [Proceed to Checkout]
       │                  │
       ├─→ Update Qty      └─→ [Login/Register]
       │                        │
       ├─→ Remove Item          └─→ [Enter Shipping Address]
       │                              │
       └─→ Continue Shopping          └─→ [Select Payment Method]
                                           │
                                           └─→ [Process Payment]
                                                │
                                                ├─→ [Payment Success]
                                                │   └─→ [Order Placed]
                                                │         └─→ [View Order]
                                                │
                                                └─→ [Payment Failed]
                                                    └─→ [Retry]

[Cashier Processing]
  │
  ├─→ [View Online Orders]
  │    └─→ [Process Payment]
  │         └─→ [Mark as Paid]
  │
  └─→ [Complete Transaction]
```

### WORKFLOW 2: POS COUNTER SALES
```
START (Cashier)
  │
  ├─→ [New Transaction] ──→ [Get Invoice Number]
  │
  ├─→ [Scan/Enter Item Code]
  │    └─→ [Check Stock]
  │         ├─→ Stock OK?
  │         │   └─→ YES ──→ [Add to Cart]
  │         │
  │         └─→ Stock NOT OK?
  │             └─→ NO ──→ [Show Error] ──→ [Try Again]
  │
  ├─→ [Adjust Items] (Loop)
  │    ├─→ Update Qty
  │    └─→ Remove Item
  │
  ├─→ [Customer Ready to Pay]
  │    │
  │    └─→ [Select Payment Method]
  │         │
  │         ├─→ Cash
  │         ├─→ Card
  │         ├─→ E-wallet
  │         └─→ Other
  │
  ├─→ [Process Payment]
  │    ├─→ Payment Success
  │    │   └─→ [Update Stock]
  │    │
  │    └─→ Payment Failed
  │         └─→ [Retry]
  │
  ├─→ [Print Receipt]
  │
  └─→ END [Complete Transaction]
```

### WORKFLOW 3: PROCUREMENT CYCLE
```
START (Supervisor)
  │
  ├─→ [Stock Analysis] ──→ [Identify Need]
  │
  ├─→ [Create PR] (Purchase Request)
  │    └─→ [Select Supplier & Items]
  │         └─→ [Submit for Approval]
  │
  ├─→ [Approve/Reject PR]
  │    │
  │    ├─→ APPROVE ──→ [Convert to PO]
  │    │                │
  │    │                └─→ [Edit Prices (Optional)]
  │    │                     │
  │    │                     └─→ [Confirm Prices]
  │    │                          │
  │    │                          └─→ [Mark PO as Sent]
  │    │
  │    └─→ REJECT ──→ [Send Feedback to Requester]
  │
  ├─→ [Supplier Delivers] ──→ [Receive Goods]
  │
  ├─→ [Create Goods Receipt (GR)]
  │    └─→ [Record Items Received]
  │         └─→ [Verify Lot/Batch/Expiry]
  │
  ├─→ [Create Inventory Record]
  │    └─→ [Update Stock in System]
  │
  ├─→ [Receive Invoice from Supplier]
  │
  ├─→ [Create Invoice Record]
  │    └─→ [Link to PO]
  │         └─→ [Upload Invoice File]
  │
  ├─→ [Process Payment to Supplier]
  │
  ├─→ [Mark Invoice as Paid]
  │    └─→ [Upload Payment Proof]
  │
  └─→ END [Procurement Complete]
```

### WORKFLOW 4: STOCK REORDER DECISION
```
START (Supervisor - Daily/Weekly)
  │
  ├─→ [View Stock Movement Analysis]
  │    │
  │    ├─→ [Fast-Moving Items Analysis]
  │    │    └─→ Identify High-Velocity Products
  │    │         └─→ Check Current Stock Level
  │    │
  │    └─→ [Slow-Moving Items Analysis]
  │         └─→ Identify Low-Velocity Products
  │             └─→ Decide Clearance/Discount
  │
  ├─→ [FOR FAST-MOVING ITEMS]
  │    └─→ [Create PR for Reorder]
  │         └─→ [Go to Procurement Workflow]
  │
  ├─→ [FOR SLOW-MOVING ITEMS]
  │    └─→ [Develop Strategy]
  │         ├─→ Discount/Promo
  │         ├─→ Bundle with Fast-Moving
  │         └─→ Clearance Sale
  │
  └─→ END [Continue Monitoring]
```

---

## 🔐 ACCESS CONTROL TREE

```
SISTEM POS TERINTEGRASI
│
├─ PUBLIC ACCESS
│  ├─ /register ─────────────────────── Customer Registration
│  ├─ /login ────────────────────────── Customer/Staff Login
│  └─ /marketplace ──────────────────── Browse Products (Guest)
│
├─ CUSTOMER ONLY (Auth + Role: customer)
│  ├─ /marketplace/cart
│  ├─ /marketplace/checkout
│  ├─ /marketplace/orders
│  └─ /profile
│
├─ CASHIER ONLY (Auth + Role: cashier)
│  ├─ /transaction (POS)
│  ├─ /cart (POS Cart)
│  ├─ /transaction/marketplace-orders
│  └─ /transaction/online-orders
│
├─ ADMIN (Auth + Role: admin)
│  ├─ /inventory/item
│  ├─ /inventory/category
│  ├─ /inventory/supplier
│  ├─ /customer
│  ├─ /report/transaction
│  └─ /profile
│
├─ SUPERVISOR (Auth + Role: supervisor)
│  ├─ /user (User Management)
│  ├─ /inventory/* (Full CRUD)
│  ├─ /stock-movement (Analysis)
│  ├─ /procurement/* (Full Workflow)
│  ├─ /payment-method
│  ├─ /absence
│  ├─ /report/* (Full Access)
│  └─ /profile
│
└─ COMBINED ACCESS (IsAdminOrSupervisor)
   ├─ /inventory/*
   ├─ /stock-movement
   ├─ /procurement
   ├─ /payment-method
   ├─ /absence
   └─ /report
```

---

## 📈 FITUR FREQUENCY MATRIX

```
FITUR                    CUSTOMER  CASHIER  ADMIN  SUPERVISOR  SUPPLIER
──────────────────────────────────────────────────────────────────────
Browse Products            ███░░     ░░░░░    ░░░░░   ░░░░░      ░░░░░
Add to Cart                ███░░     ██░░░    ░░░░░   ░░░░░      ░░░░░
Checkout & Payment         ███░░     ██░░░    ░░░░░   ░░░░░      ░░░░░
View Orders                ███░░     ██░░░    ░░░░░   ░░░░░      ░░░░░
POS Transaction            ░░░░░     ███░░    ░░░░░   ██░░░      ░░░░░
Manage Inventory           ░░░░░     ░░░░░    ███░░   ████░      ░░░░░
Stock Analysis             ░░░░░     ░░░░░    ░░░░░   ████░      ░░░░░
Create PR                  ░░░░░     ░░░░░    ░░░░░   ████░      ░░░░░
Approve PR                 ░░░░░     ░░░░░    ░░░░░   ████░      ░░░░░
Create PO                  ░░░░░     ░░░░░    ░░░░░   ████░      ░░░░░
Create GR                  ░░░░░     ░░░░░    ░░░░░   ████░      ░░░░░
Invoice Management         ░░░░░     ░░░░░    ░░░░░   ████░      ░░░░░
User Management            ░░░░░     ░░░░░    ░░░░░   ████░      ░░░░░
Reporting                  ░░░░░     ░░░░░    ██░░░   ████░      ░░░░░
Profile Management         ██░░░     ██░░░    ██░░░   ██░░░      ░░░░░

Legend: ████░ = Full Access, ███░░ = High Usage, ██░░░ = Medium, █░░░░ = Low
```

---

## 📊 SUBSYSTEM & FEATURE COUNT

```
SUBSYSTEM                  USE CASES  FEATURES  PRIMARY ACTORS
─────────────────────────────────────────────────────────────
Marketplace                13         13        Customer, Cashier
POS System                 10         10        Cashier, Supervisor
Online Order Processing    4          4         Cashier, Supervisor
Inventory Management       11         11        Admin, Supervisor
Procurement Workflow       13         13        Supervisor, Admin
User Management            3          3         Supervisor
Reporting & Analytics      2+         5+        Supervisor, Admin, Owner
────────────────────────────────────────────────────────────
TOTAL                      56+        59+       All Actors
```

---

## ⚡ QUICK FEATURE LOOKUP

**Jika Anda Ingin:**

| Tujuan | Go To | Aktor |
|--------|-------|-------|
| Berbelanja Online | Marketplace | Customer |
| Transaksi Counter | POS System | Cashier |
| Tambah Produk Baru | Inventory Management | Admin/Supervisor |
| Order Barang dari Supplier | Procurement | Supervisor |
| Lihat Stock Trend | Stock Movement | Supervisor |
| Export Laporan Penjualan | Reporting | Supervisor/Admin |
| Manage Karyawan | User Management | Supervisor |
| Monitor Business KPI | Dashboard | Owner |

---

## 🎯 FEATURE COMPLETION STATUS

| Subsystem | Status | % Complete | Notes |
|-----------|--------|-----------|-------|
| Marketplace | ✅ | 100% | Fully Implemented |
| POS | ✅ | 100% | Fully Implemented |
| Online Orders | ✅ | 100% | Fully Implemented |
| Inventory | ✅ | 100% | Fully Implemented |
| Procurement | ✅ | 100% | Fully Implemented |
| User Management | ✅ | 100% | Fully Implemented |
| Reporting | ✅ | 95% | Minor refinements needed |
| Stock Analysis | ✅ | 90% | Core features done |

---

*Document Generated: February 5, 2026*  
*Last Updated: February 5, 2026*  
*Version: 1.0 - Complete & Production Ready*
