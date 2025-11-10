Role & Feature Mapping
======================

Summary of enforced role permissions and changes made to the codebase on 2025-11-11 to align the app with the requested spec.

Roles: owner, admin, supervisor, cashier, customer

What I changed
- Route protections: updated `routes/web.php` to protect route groups with role middleware
  - Added `IsAdminOrSupervisor` and `IsCashier` middleware classes for common OR-role checks.
  - Tightened `IsAdmin` to only allow `admin` and `owner` (removed supervisor so we can model supervisor separately).
  - Applied `IsAdminOrSupervisor` to procurement dashboard, goods receipts, invoices, inventory records, stock movement, reports, and new purchase order areas.
  - Applied `IsCashier` to POS/cart/transaction processing and marketplace order processing that cashiers handle.
  - Restricted internal Customer CRUD to Admin (internal customers) via `IsAdmin` middleware.
  - Restricted Absence and Payment Method management to admin/owner/supervisor via `IsAdminOrSupervisor`.

Views/navigation
- Updated `resources/views/components/layout.blade.php` so sidebar links show/hide based on `auth()->user()->role`:
  - Internal "Pelanggan" (customers) is now only visible to `admin` and `owner`.
  - "Gudang" (inventory & procurement submenu) visible to `admin`, `owner`, `supervisor`.
  - Payment Methods, Pegawai (user management) and Absensi visible to `owner`, `supervisor`, `admin`.
  - Laporan visible to `owner`, `admin`, `supervisor`.

Files added/edited (high level)
- Added middleware:
  - `app/Http/Middleware/RoleMiddleware.php` (parameterized, not yet registered in Kernel — left for later if desired)
  - `app/Http/Middleware/IsAdminOrSupervisor.php`
  - `app/Http/Middleware/IsCashier.php`
- Edited middleware:
  - `app/Http/Middleware/IsAdmin.php` (narrowed to admin + owner)
- Edited routes:
  - `routes/web.php` (attached role middlewares across groups)
- Edited views:
  - `resources/views/components/layout.blade.php` (sidebar visibility rules)

Next steps (recommended)
1. Backfill `customers` table into `users` (create migration/script, update `transactions.user_id`) — currently pending in todo list.
2. Register `RoleMiddleware` in `app/Http/Kernel.php` if you prefer the parameterized `role:` route syntax.
3. Add automated feature tests for each role's happy path (admin inventory CRUD, supervisor PR approval, cashier POS flows, customer marketplace checkout).
4. Manually smoke-test important flows (create PO as admin, approve PR as supervisor, create transaction as cashier, checkout as customer).
5. Restart long-running services (queue workers, php-fpm) so middleware bootstrap changes are picked up.

How to revert a change quickly
- If you want to revert routing changes, use git to checkout `routes/web.php` from previous commit. I can also prepare a patch to revert specific changes on request.

If you'd like, I can now:
- run the test plan and add at least 3 quick feature tests, and/or
- create the customers->users backfill script and migration (safe, idempotent), or
- register `RoleMiddleware` in `Kernel.php` and switch route syntax to `role:admin,supervisor` style.

