-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2026 at 07:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravelpos`
--

-- --------------------------------------------------------

--
-- Table structure for table `absences`
--

CREATE TABLE `absences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `login_at` time NOT NULL,
  `logout_at` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absences`
--

INSERT INTO `absences` (`id`, `user_id`, `login_at`, `logout_at`, `created_at`, `updated_at`) VALUES
(1, 1, '19:31:00', '19:32:00', '2025-11-27 12:31:04', '2025-11-27 12:32:32'),
(2, 3, '19:32:00', '19:33:00', '2025-11-27 12:32:37', '2025-11-27 12:33:17'),
(3, 1, '19:33:00', '19:33:00', '2025-11-27 12:33:28', '2025-11-27 12:33:45'),
(4, 3, '19:33:00', NULL, '2025-11-27 12:33:52', '2025-11-27 12:33:52'),
(5, 3, '15:16:00', NULL, '2025-12-09 08:16:14', '2025-12-09 08:16:14'),
(6, 3, '22:06:00', NULL, '2025-12-10 15:06:16', '2025-12-10 15:06:16'),
(7, 1, '07:59:00', '07:59:00', '2026-01-27 00:59:15', '2026-01-27 00:59:44'),
(8, 3, '07:59:00', '08:02:00', '2026-01-27 00:59:55', '2026-01-27 01:02:43'),
(9, 4, '08:03:00', '08:04:00', '2026-01-27 01:03:01', '2026-01-27 01:04:34'),
(10, 5, '08:12:00', '08:15:00', '2026-01-27 01:12:46', '2026-01-27 01:15:52'),
(11, 5, '08:16:00', '08:20:00', '2026-01-27 01:16:26', '2026-01-27 01:20:10'),
(12, 4, '08:20:00', '08:22:00', '2026-01-27 01:20:23', '2026-01-27 01:22:53'),
(13, 4, '08:23:00', '08:26:00', '2026-01-27 01:23:04', '2026-01-27 01:26:34'),
(14, 4, '08:32:00', '08:33:00', '2026-01-27 01:32:59', '2026-01-27 01:33:32'),
(15, 4, '08:33:00', '08:34:00', '2026-01-27 01:33:39', '2026-01-27 01:34:14'),
(16, 3, '08:34:00', '08:36:00', '2026-01-27 01:34:43', '2026-01-27 01:36:46'),
(17, 5, '08:37:00', '08:39:00', '2026-01-27 01:37:02', '2026-01-27 01:39:40'),
(18, 3, '08:39:00', '08:41:00', '2026-01-27 01:39:47', '2026-01-27 01:41:01'),
(19, 5, '08:41:00', '08:47:00', '2026-01-27 01:41:13', '2026-01-27 01:47:11'),
(20, 3, '08:47:00', '08:49:00', '2026-01-27 01:47:21', '2026-01-27 01:49:42'),
(21, 5, '08:49:00', '08:57:00', '2026-01-27 01:49:49', '2026-01-27 01:57:20'),
(22, 3, '08:57:00', '08:58:00', '2026-01-27 01:57:31', '2026-01-27 01:58:14'),
(23, 5, '08:58:00', '09:12:00', '2026-01-27 01:58:41', '2026-01-27 02:12:34'),
(24, 1, '09:12:00', '09:20:00', '2026-01-27 02:12:46', '2026-01-27 02:20:43'),
(25, 3, '09:21:00', '09:23:00', '2026-01-27 02:21:54', '2026-01-27 02:23:28'),
(26, 5, '09:22:00', '09:24:00', '2026-01-27 02:22:40', '2026-01-27 02:24:12'),
(27, 3, '09:24:00', '09:25:00', '2026-01-27 02:24:16', '2026-01-27 02:25:20'),
(28, 5, '09:25:00', '09:27:00', '2026-01-27 02:25:27', '2026-01-27 02:27:01'),
(29, 5, '09:27:00', '09:28:00', '2026-01-27 02:27:43', '2026-01-27 02:28:47'),
(30, 3, '09:29:00', '11:24:00', '2026-01-27 02:29:09', '2026-01-27 04:24:51'),
(31, 4, '11:25:00', '11:29:00', '2026-01-27 04:25:01', '2026-01-27 04:29:29'),
(32, 3, '11:29:00', NULL, '2026-01-27 04:29:50', '2026-01-27 04:29:50');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Makanan dan Minuman', NULL, '2025-12-10 16:05:40', '2025-12-10 16:05:40'),
(2, 'Kertas dan Alat Tulis', NULL, '2026-01-27 01:14:10', '2026-01-27 01:14:10'),
(3, 'Alat Rumah Tangga', NULL, '2026-01-27 01:14:23', '2026-01-27 01:14:23'),
(4, 'Electronic', NULL, '2026-01-27 01:14:44', '2026-01-27 01:14:44');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipts`
--

CREATE TABLE `goods_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `gr_number` varchar(255) NOT NULL,
  `receipt_date` date NOT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `notes` text DEFAULT NULL,
  `receipt_document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipts`
--

INSERT INTO `goods_receipts` (`id`, `purchase_order_id`, `gr_number`, `receipt_date`, `received_by`, `status`, `notes`, `receipt_document`, `created_at`, `updated_at`) VALUES
(1, 1, 'GR-0001/01/2026', '2026-01-27', 5, 'completed', NULL, 'receipts/uWRKtel0GmWVJSaYccrzGhj2yh5BKMTSVG3Vbacu.png', '2026-01-27 01:44:56', '2026-01-27 01:44:56'),
(2, 2, 'GR-0002/01/2026', '2026-01-27', 5, 'completed', NULL, 'receipts/hDibyHPx1qkG26PDEJyDejf0mebN1MvQUmtCfDib.jpg', '2026-01-27 01:59:45', '2026-01-27 01:59:45');

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipt_items`
--

CREATE TABLE `goods_receipt_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_receipt_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity_received` int(11) NOT NULL,
  `remaining_quantity` int(11) NOT NULL DEFAULT 0,
  `expiry_date` date DEFAULT NULL,
  `lot_code` varchar(255) DEFAULT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `expiry_status` enum('safe','warning','expired') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipt_items`
--

INSERT INTO `goods_receipt_items` (`id`, `goods_receipt_id`, `item_id`, `product_name`, `unit`, `quantity_received`, `remaining_quantity`, `expiry_date`, `lot_code`, `batch_number`, `expiry_status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Cairan Pembersih Lantai Wipol 750ML', 'box', 5, 5, '2028-10-17', NULL, 'CAI260127001', 'safe', NULL, '2026-01-27 01:44:56', '2026-01-27 01:44:56'),
(2, 1, NULL, 'Cairan Pembersih Lantai Porselen Vixal 750M', 'box', 2, 2, '2029-10-31', NULL, 'CAI260127002', 'safe', NULL, '2026-01-27 01:44:56', '2026-01-27 01:44:56'),
(3, 2, NULL, 'Box of 240 mL', 'box', 5, 5, '2031-11-13', NULL, 'BOX260127001', 'safe', NULL, '2026-01-27 01:59:45', '2026-01-27 01:59:45'),
(4, 2, NULL, '600 mL', 'pcs', 100, 100, '2029-10-19', NULL, '600260127001', 'safe', NULL, '2026-01-27 01:59:45', '2026-01-27 01:59:45');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_batches`
--

CREATE TABLE `inventory_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `qty_on_hand` decimal(12,2) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `lot_code` varchar(255) DEFAULT NULL,
  `unit_cost` decimal(12,2) DEFAULT NULL,
  `location_code` varchar(255) DEFAULT NULL,
  `status` enum('active','expired','quarantined') NOT NULL DEFAULT 'active',
  `last_tx_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_receipt_item_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out') NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `ref_type` varchar(255) NOT NULL,
  `ref_id` bigint(20) UNSIGNED NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_movements`
--

INSERT INTO `inventory_movements` (`id`, `goods_receipt_item_id`, `type`, `qty`, `ref_type`, `ref_id`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 'in', 5.00, 'GR', 1, 'Penerimaan barang dari GR #GR-0001/01/2026', '2026-01-27 01:44:56', '2026-01-27 01:44:56'),
(2, 2, 'in', 2.00, 'GR', 1, 'Penerimaan barang dari GR #GR-0001/01/2026', '2026-01-27 01:44:56', '2026-01-27 01:44:56'),
(3, 3, 'in', 5.00, 'GR', 2, 'Penerimaan barang dari GR #GR-0002/01/2026', '2026-01-27 01:59:45', '2026-01-27 01:59:45'),
(4, 4, 'in', 100.00, 'GR', 2, 'Penerimaan barang dari GR #GR-0002/01/2026', '2026-01-27 01:59:45', '2026-01-27 01:59:45');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_records`
--

CREATE TABLE `inventory_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_receipt_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` enum('in','out') NOT NULL,
  `batch_number` varchar(255) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_settings`
--

CREATE TABLE `inventory_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `analysis_period` int(11) NOT NULL DEFAULT 30,
  `fast_moving_threshold` decimal(8,2) NOT NULL DEFAULT 3.00,
  `slow_moving_threshold` decimal(8,2) NOT NULL DEFAULT 0.50,
  `lead_time_days` int(11) NOT NULL DEFAULT 5,
  `safety_stock_days` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `invoice_file` varchar(255) DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `purchase_order_id`, `invoice_number`, `invoice_date`, `due_date`, `amount`, `status`, `invoice_file`, `payment_proof`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '123123123', '2026-01-27', '2026-02-26', 900000.00, 'completed', 'invoices/YbehUkqA6DQdTeDjOYqBcHnmClOEmgBiS1R3jMuj.jpg', NULL, NULL, '2026-01-27 02:00:11', '2026-01-27 02:00:11'),
(2, 2, '123123', '2026-01-27', '2026-02-26', 1050000.00, 'completed', 'invoices/O6WGFFLhPMY2EFy7ldMv0GXyfSPrz7xaiDehFnot.jpg', 'payment_proofs/yJK5jgsySsIK6fOzTbFZfjwaAESn9Xczo57KSqLV.png', NULL, '2026-01-27 02:06:51', '2026-01-27 02:06:51');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cost_price` decimal(12,2) NOT NULL,
  `selling_price` decimal(12,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `picture` varchar(255) DEFAULT NULL,
  `requires_expiry` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `category_id`, `code`, `name`, `cost_price`, `selling_price`, `stock`, `picture`, `requires_expiry`, `created_at`, `updated_at`) VALUES
(1, 1, 'UNWI1731', 'Lee Mineral 600 ml', 4000.00, 5000.00, 75, '1769475684.png', 0, '2025-12-10 16:06:16', '2026-01-27 01:01:24'),
(2, 1, 'GDDE9390', 'Lee Mineral 1500 ml', 5000.00, 7000.00, 114, '1769475666.png', 0, '2025-12-10 16:06:37', '2026-01-27 01:03:40'),
(3, 1, 'LINF7270', 'Aqua Mineral 600ml', 3000.00, 4000.00, 4, '1769475641.png', 0, '2025-12-10 16:07:06', '2026-01-27 01:00:41'),
(4, 1, 'CEEF2251', 'Indomie Goreng Rendang', 3000.00, 4500.00, 95, '1769475653.png', 0, '2025-12-10 16:11:48', '2026-01-27 01:00:53'),
(5, 4, 'LFQU5282', 'Baterai Alkaline AAA', 10000.00, 12000.00, 97, '1769476548.jpeg', 0, '2026-01-27 01:15:48', '2026-01-27 01:15:48');

-- --------------------------------------------------------

--
-- Table structure for table `item_supplier`
--

CREATE TABLE `item_supplier` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_orders`
--

CREATE TABLE `marketplace_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(32) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `pickup_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `total_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketplace_orders`
--

INSERT INTO `marketplace_orders` (`id`, `user_id`, `code`, `status`, `pickup_name`, `phone`, `notes`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 2, 'PO-UIBBONDE', 'completed', 'Syafiq Muhammad Alif', '0895701033483', 'ambil sore ini', 23500.00, '2026-01-27 01:27:20', '2026-01-27 01:34:08'),
(2, 2, 'PO-8DROGVES', 'pending_pickup', 'Syafiq Muhammad Alif', '1231122232122', 'ambil sekarang', 32500.00, '2026-01-27 01:32:23', '2026-01-27 01:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_order_items`
--

CREATE TABLE `marketplace_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketplace_order_items`
--

INSERT INTO `marketplace_order_items` (`id`, `order_id`, `item_id`, `qty`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 12000.00, '2026-01-27 01:27:20', '2026-01-27 01:27:20'),
(2, 1, 4, 1, 4500.00, '2026-01-27 01:27:20', '2026-01-27 01:27:20'),
(3, 1, 2, 1, 7000.00, '2026-01-27 01:27:20', '2026-01-27 01:27:20'),
(4, 2, 3, 1, 4000.00, '2026-01-27 01:32:23', '2026-01-27 01:32:23'),
(5, 2, 5, 2, 12000.00, '2026-01-27 01:32:23', '2026-01-27 01:32:23'),
(6, 2, 4, 1, 4500.00, '2026-01-27 01:32:23', '2026-01-27 01:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_items_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '2024_05_21_174125_create_categories_table', 1),
(5, '2024_05_21_174227_create_customers_table', 1),
(6, '2024_05_21_174511_create_payment_methods_table', 1),
(7, '2024_05_21_175122_create_item_supplier_table', 1),
(8, '2024_05_21_175123_create_wholesale_prices_table', 1),
(9, '2024_05_21_182615_create_carts_table', 1),
(10, '2024_05_22_030109_create_transactions_table', 1),
(11, '2024_05_22_030902_create_transaction_details_table', 1),
(12, '2024_05_27_072011_create_absences_table', 1),
(13, '2024_10_28_000001_create_inventory_settings_table', 1),
(14, '2024_10_28_000002_create_stock_movement_analyses_table', 1),
(15, '2024_10_28_000003_create_sessions_table', 1),
(16, '2025_07_23_105030_create_supplier_products_table', 1),
(17, '2025_09_03_000000_add_customer_role_and_contact_to_users_table', 1),
(18, '2025_09_04_000001_create_marketplace_orders_table', 1),
(19, '2025_09_04_000002_create_marketplace_order_items_table', 1),
(20, '2025_10_08_010229_add_online_fields_to_transactions_table', 1),
(21, '2025_10_13_000001_create_purchase_requests_table', 1),
(22, '2025_10_13_000002_create_purchase_request_items_table', 1),
(23, '2025_10_13_000003_create_purchase_orders_table', 1),
(24, '2025_10_13_000004_create_goods_receipts_table', 1),
(25, '2025_10_13_000004_create_purchase_order_items_table', 1),
(26, '2025_10_13_000005_create_goods_receipt_items_table', 1),
(27, '2025_10_13_000006_create_inventory_movements_table', 1),
(28, '2025_10_13_000007_create_invoices_table', 1),
(29, '2025_10_23_000007_add_required_columns_to_purchase_orders_and_items', 1),
(30, '2025_10_23_000008_make_purchase_order_items_item_id_nullable', 1),
(31, '2025_10_27_000001_add_username_and_picture_to_users', 1),
(32, '2025_11_05_234855_create_inventory_records_table', 1),
(33, '2025_11_06_000001_add_expiry_columns_to_goods_receipt_items', 1),
(34, '2025_11_07_000001_modify_item_id_in_goods_receipt_items', 1),
(35, '2025_11_07_232719_create_cart_items_table', 1),
(36, '2025_11_11_224914_create_password_reset_tokens_table', 1),
(37, '2025_11_27_190802_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Tunai',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Tunai', NULL, '2025-11-27 12:32:49', '2025-11-27 12:32:49');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `po_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `prices_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `purchase_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `estimated_delivery_date` date DEFAULT NULL,
  `confirmation_date` datetime DEFAULT NULL,
  `confirmed_delivery_date` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `supplier_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `supplier_notes` text DEFAULT NULL,
  `invoice_image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_number`, `supplier_id`, `po_date`, `status`, `created_by`, `prices_confirmed`, `total_amount`, `purchase_request_id`, `contact_person`, `contact_phone`, `estimated_delivery_date`, `confirmation_date`, `confirmed_delivery_date`, `sent_at`, `supplier_confirmed`, `supplier_notes`, `invoice_image_path`, `created_at`, `updated_at`) VALUES
(1, 'PO/2026/01/0001', 2, '2026-01-27', 'completed', 5, 1, 900000.00, 3, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-01-27 01:41:54', '2026-01-27 02:00:11'),
(2, 'PO/2026/01/0002', 1, '2026-01-27', 'completed', 5, 1, 1050000.00, 4, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-01-27 01:58:55', '2026-01-27 02:06:51');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED,
  `notes` text DEFAULT NULL,
  `is_estimated_price` tinyint(1) NOT NULL DEFAULT 0,
  `price_notes` text DEFAULT NULL,
  `final_unit_price` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `item_id`, `product_name`, `quantity`, `unit`, `unit_price`, `notes`, `is_estimated_price`, `price_notes`, `final_unit_price`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Cairan Pembersih Lantai Wipol 750ML', 5, 'box', 100000.00, NULL, 0, NULL, NULL, '2026-01-27 01:41:54', '2026-01-27 01:43:13'),
(2, 1, NULL, 'Cairan Pembersih Lantai Porselen Vixal 750M', 2, 'box', 200000.00, NULL, 0, NULL, NULL, '2026-01-27 01:41:54', '2026-01-27 01:43:13'),
(3, 2, NULL, 'Box of 240 mL', 5, 'box', 110000.00, NULL, 0, NULL, NULL, '2026-01-27 01:58:55', '2026-01-27 01:59:18'),
(4, 2, NULL, '600 mL', 100, 'pcs', 5000.00, NULL, 0, NULL, NULL, '2026-01-27 01:58:55', '2026-01-27 01:59:18');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pr_number` varchar(255) NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `request_date` date NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_status` varchar(255) NOT NULL DEFAULT 'pending',
  `approval_notes` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `validation_document_path` varchar(255) DEFAULT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `pr_number`, `requested_by`, `request_date`, `supplier_id`, `status`, `approved_by`, `approved_at`, `approval_status`, `approval_notes`, `rejection_reason`, `validation_document_path`, `is_validated`, `description`, `created_at`, `updated_at`) VALUES
(1, 'PR-0001/01/2026', 5, '2026-01-27', 1, 'pending', NULL, NULL, 'pending', NULL, NULL, NULL, 0, NULL, '2026-01-27 01:38:42', '2026-01-27 01:38:42'),
(2, 'PR-0002/01/2026', 5, '2026-01-27', 2, 'rejected', 3, '2026-01-27 01:40:50', 'pending', NULL, 'Ditolak Persetujuan', NULL, 0, NULL, '2026-01-27 01:39:10', '2026-01-27 01:40:50'),
(3, 'PR-0003/01/2026', 5, '2026-01-27', 2, 'po_created', 3, '2026-01-27 01:40:26', 'approved', NULL, NULL, NULL, 0, NULL, '2026-01-27 01:39:35', '2026-01-27 01:41:54'),
(4, 'PR-0004/01/2026', 5, '2026-01-27', 1, 'po_created', 3, '2026-01-27 01:58:01', 'approved', 'Menyetujui Permintaan', NULL, NULL, 0, NULL, '2026-01-27 01:57:11', '2026-01-27 01:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_items`
--

CREATE TABLE `purchase_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_request_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'pcs',
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(15,2) DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_request_items`
--

INSERT INTO `purchase_request_items` (`id`, `purchase_request_id`, `product_name`, `quantity`, `unit`, `current_stock`, `unit_price`, `supplier_id`, `item_id`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Box of 600 mL', 5, 'box', 0, NULL, NULL, NULL, NULL, '2026-01-27 01:38:42', '2026-01-27 01:38:42'),
(2, 1, 'Box of 1500 mL', 5, 'box', 0, NULL, NULL, NULL, NULL, '2026-01-27 01:38:42', '2026-01-27 01:38:42'),
(3, 2, 'PDN Sunlight  Sabun Cuci Piring Sunlight Lime  650ml 1 Sunlight Sabun Cuci Piring Sunlight Lime 650ml', 20, 'pcs', 0, NULL, NULL, NULL, NULL, '2026-01-27 01:39:10', '2026-01-27 01:39:10'),
(4, 3, 'Cairan Pembersih Lantai Wipol 750ML', 5, 'box', 0, NULL, NULL, NULL, NULL, '2026-01-27 01:39:35', '2026-01-27 01:39:35'),
(5, 3, 'Cairan Pembersih Lantai Porselen Vixal 750M', 2, 'box', 0, NULL, NULL, NULL, NULL, '2026-01-27 01:39:35', '2026-01-27 01:39:35'),
(6, 4, 'Box of 240 mL', 5, 'box', 0, NULL, NULL, NULL, NULL, '2026-01-27 01:57:11', '2026-01-27 01:57:11'),
(7, 4, '600 mL', 100, 'pcs', 0, NULL, NULL, NULL, NULL, '2026-01-27 01:57:11', '2026-01-27 01:57:11');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('VhnpK7fhlLka42SDjVDser5nQRSnFyYoYTwwuSTZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibE1QRkpQU0ZuSHhvTDlzZEVzSklZakw0TFZHM2lXOFZlMXdmUXBsNyI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YToxOntzOjI2OiIwMUtGWVZERjRYQVo2Tk5aUkdRQlcyRVZNNyI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769488170),
('wgYp6k1v5I0TMiuDgKKuuSvXTtF7et7HsFvk7vp7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV01yaEVsa1NiWFlBRGFxUjRCYXp1SXVFcHlJVFlVdElmV0NWQzRSSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fX0=', 1769480609),
('wln9zrlyzKXXhJGpxRAclTMNUQusxlWHgH5GleOI', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoia0M4ZHI4VWlFa1hYV3IxUnJoMFRxNzRid2REM0p3QVRvSEhYU21LTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3RyYW5zYWN0aW9uL29ubGluZS1vcmRlcnMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2ludmVudG9yeS9zdXBwbGllciI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9fQ==', 1769488223);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movement_analyses`
--

CREATE TABLE `stock_movement_analyses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `total_sold_30_days` int(11) NOT NULL DEFAULT 0,
  `avg_daily_sales` decimal(8,2) NOT NULL DEFAULT 0.00,
  `movement_status` enum('FAST','NORMAL','SLOW') NOT NULL DEFAULT 'NORMAL',
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `days_until_empty` int(11) DEFAULT NULL,
  `non_moving_days` int(11) NOT NULL DEFAULT 0,
  `stuck_stock_value` decimal(15,2) NOT NULL DEFAULT 0.00,
  `recommendation` varchar(255) DEFAULT NULL,
  `suggested_reorder_qty` int(11) DEFAULT NULL,
  `last_sale_date` timestamp NULL DEFAULT NULL,
  `last_analysis_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `address`, `email`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Oasis Waters Internationa', '0711432446', 'Jl. Kantor Lurah Lorong Anggrek RT 21 RW 04 Sukomoro Km. 18, Lawang Kidul, Kec. Ilir Tim. II, Kab. Banyuasin, Sumatera Selatan 30961', NULL, NULL, '2025-12-09 08:22:58', '2025-12-09 08:22:58'),
(2, 'PT. Delta Muster Rise', '089215644885', 'Jl. Pangeran Ayin Komp. Sako Permai Blok E No. 5, Kel. Sako Baru Palembang, Sumatera Selatan, Indonesia', NULL, NULL, '2025-12-09 08:24:34', '2025-12-09 08:24:34');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_products`
--

CREATE TABLE `supplier_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `min_order` int(11) NOT NULL DEFAULT 1,
  `lead_time` int(11) DEFAULT NULL COMMENT 'Lead time in days',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_products`
--

INSERT INTO `supplier_products` (`id`, `supplier_id`, `item_id`, `product_name`, `price`, `min_order`, `lead_time`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Box of 600 mL', NULL, 1, NULL, '2025-12-09 08:22:58', '2025-12-09 08:22:58'),
(2, 1, NULL, '600 mL', NULL, 1, NULL, '2025-12-09 08:22:58', '2025-12-09 08:22:58'),
(3, 1, NULL, 'Box of 240 mL', NULL, 1, NULL, '2025-12-09 08:22:58', '2025-12-09 08:22:58'),
(4, 1, NULL, '240 mL', NULL, 1, NULL, '2025-12-09 08:22:58', '2025-12-09 08:22:58'),
(5, 1, NULL, 'Box of 1500 mL', NULL, 1, NULL, '2025-12-09 08:22:58', '2025-12-09 08:22:58'),
(6, 1, NULL, '1500 mL', NULL, 1, NULL, '2025-12-09 08:22:58', '2025-12-09 08:22:58'),
(7, 2, NULL, 'PDN Sunlight  Sabun Cuci Piring Sunlight Lime  650ml 1 Sunlight Sabun Cuci Piring Sunlight Lime 650ml', NULL, 1, NULL, '2025-12-09 08:24:35', '2025-12-09 08:24:35'),
(8, 2, NULL, 'Cairan Pembersih Lantai Wipol 750ML', NULL, 1, NULL, '2025-12-09 08:24:35', '2025-12-09 08:24:35'),
(9, 2, NULL, 'Cairan Pembersih Lantai Porselen Vixal 750M', NULL, 1, NULL, '2025-12-09 08:24:35', '2025-12-09 08:24:35');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice` varchar(255) NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `channel` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `change` int(11) NOT NULL DEFAULT 0,
  `status` enum('paid','debt') NOT NULL DEFAULT 'paid',
  `payment_status` varchar(255) DEFAULT NULL,
  `pickup_status` varchar(255) DEFAULT NULL,
  `pickup_code` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `customer_id`, `invoice`, `invoice_no`, `total`, `discount`, `payment_method_id`, `channel`, `amount`, `change`, `status`, `payment_status`, `pickup_status`, `pickup_code`, `note`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, '1012250001', '1', 495000, 0, 1, NULL, 495000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan Kirana, 10/12/2025 23:08)', '2025-12-10 16:08:22', '2025-12-10 16:08:22'),
(2, 3, NULL, '1012250002', '2', 160000, 0, 1, NULL, 160000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan Kirana, 10/12/2025 23:09)', '2025-12-10 16:09:17', '2025-12-10 16:09:17'),
(3, 3, NULL, '1012250003', '3', 60000, 0, 1, NULL, 60000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan Kirana, 10/12/2025 23:10)', '2025-12-10 16:10:10', '2025-12-10 16:10:10'),
(4, 3, NULL, '1012250004', '4', 13500, 0, 1, NULL, 13500, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan Kirana, 10/12/2025 23:12)', '2025-12-10 16:12:21', '2025-12-10 16:12:21'),
(5, 4, NULL, '2701260001', '1', 35000, 0, 1, NULL, 35000, 0, 'paid', NULL, NULL, NULL, '(diproses: farhan, 27/01/2026 08:03)', '2026-01-27 01:03:40', '2026-01-27 01:03:40'),
(6, 2, NULL, '2701260002', '2', 23500, 0, 1, 'online', 23500, 0, 'paid', 'paid', 'picked_up', 'PO-UIBBONDE', 'Marketplace pickup: Syafiq Muhammad Alif (0895701033483)', '2026-01-27 01:34:08', '2026-01-27 01:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `item_price` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `item_id`, `qty`, `item_price`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 40, 4000, 160000, '2025-12-10 16:08:22', '2025-12-10 16:08:22'),
(2, 1, 2, 30, 7000, 210000, '2025-12-10 16:08:22', '2025-12-10 16:08:22'),
(3, 1, 1, 25, 5000, 125000, '2025-12-10 16:08:22', '2025-12-10 16:08:22'),
(4, 2, 3, 40, 4000, 160000, '2025-12-10 16:09:17', '2025-12-10 16:09:17'),
(5, 3, 3, 15, 4000, 60000, '2025-12-10 16:10:10', '2025-12-10 16:10:10'),
(6, 4, 4, 3, 4500, 13500, '2025-12-10 16:12:21', '2025-12-10 16:12:21'),
(7, 5, 2, 5, 7000, 35000, '2026-01-27 01:03:40', '2026-01-27 01:03:40'),
(8, 6, 5, 1, 12000, 12000, '2026-01-27 01:34:08', '2026-01-27 01:34:08'),
(9, 6, 4, 1, 4500, 4500, '2026-01-27 01:34:08', '2026-01-27 01:34:08'),
(10, 6, 2, 1, 7000, 7000, '2026-01-27 01:34:08', '2026-01-27 01:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `position` varchar(255) DEFAULT NULL,
  `picture` varchar(255) NOT NULL DEFAULT 'profile.jpg',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `address`, `email_verified_at`, `password`, `role`, `position`, `picture`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'admin@example.com', NULL, NULL, NULL, '$2y$12$K3OrsGH/B3pYIKN6.ThQ6OrR1/6yIM5t0dyjBNoIku3G8Hk2lncpC', 'owner', NULL, 'profile.jpg', 'active', NULL, '2025-11-27 12:19:02', '2025-11-27 12:19:02'),
(2, 'Syafiq Muhammad Alif', 'syafiq', 'sq.ey115@gmail.com', '0989234234', 'gandus', NULL, '$2y$12$AtGZOrLCEqpiTAmhYg7c7eK8OakpcWFwNLYTKdPjC/FmAturjRUQO', 'customer', NULL, 'profile.jpg', 'active', 'qUEBOwdymJ6mgTIzcCbzeF3pN8MN5EyvlWRXaSXstPIa9YFk0kGzL73UVVGx', '2025-11-27 12:19:57', '2025-11-27 12:26:01'),
(3, 'jihan Kirana', 'jihan', 'onetumbal87@gmail.com', '0895701033483', NULL, NULL, '$2y$12$zlaIUin4NErwjNJ86cD4feAqah59zibPmiEYEXzdUzJq3e4UOHJ72', 'supervisor', NULL, '1769475711.jpg', 'active', NULL, '2025-11-27 12:32:26', '2026-01-27 01:01:51'),
(4, 'farhan', 'farhan', 'farhan322@gmail.com', '08957010334833', NULL, NULL, '$2y$12$0F2i7FLgdXuBrAaIzfzzKeQgIT7AMbda90/oXVA8VUrjkhr14ACvu', 'cashier', NULL, '1765268289.jpg', 'active', NULL, '2025-12-09 08:18:09', '2025-12-09 08:18:09'),
(5, 'luis Suarez', 'suarez', 'suarez121@Gmail.com', '08951611373300', NULL, NULL, '$2y$12$XAdZoS.fi8kd2eMSrEuXv.oNydf/xRM9xOmiJ3VhVKThVAoZ54ZSe', 'admin', NULL, '1765268379.png', 'active', NULL, '2025-12-09 08:19:39', '2025-12-09 08:19:39');

-- --------------------------------------------------------

--
-- Table structure for table `wholesale_prices`
--

CREATE TABLE `wholesale_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `min_qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absences_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_item_id_foreign` (`item_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_user_id_item_id_unique` (`user_id`,`item_id`),
  ADD KEY `cart_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goods_receipts_gr_number_unique` (`gr_number`),
  ADD KEY `goods_receipts_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `goods_receipts_received_by_foreign` (`received_by`);

--
-- Indexes for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_items_goods_receipt_id_foreign` (`goods_receipt_id`),
  ADD KEY `goods_receipt_items_lot_code_index` (`lot_code`),
  ADD KEY `goods_receipt_items_item_id_expiry_date_index` (`item_id`,`expiry_date`),
  ADD KEY `goods_receipt_items_batch_number_index` (`batch_number`);

--
-- Indexes for table `inventory_batches`
--
ALTER TABLE `inventory_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_batches_lot_code_index` (`lot_code`),
  ADD KEY `inventory_batches_item_id_expiry_date_index` (`item_id`,`expiry_date`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_goods_receipt_item_id_foreign` (`goods_receipt_item_id`),
  ADD KEY `inventory_movements_ref_type_ref_id_index` (`ref_type`,`ref_id`);

--
-- Indexes for table `inventory_records`
--
ALTER TABLE `inventory_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_records_goods_receipt_id_foreign` (`goods_receipt_id`);

--
-- Indexes for table `inventory_settings`
--
ALTER TABLE `inventory_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_code_unique` (`code`),
  ADD KEY `items_category_id_foreign` (`category_id`);

--
-- Indexes for table `item_supplier`
--
ALTER TABLE `item_supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_supplier_item_id_supplier_id_unique` (`item_id`,`supplier_id`),
  ADD KEY `item_supplier_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `marketplace_orders_code_unique` (`code`),
  ADD KEY `marketplace_orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `marketplace_order_items`
--
ALTER TABLE `marketplace_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marketplace_order_items_order_id_foreign` (`order_id`),
  ADD KEY `marketplace_order_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD KEY `password_reset_tokens_email_index` (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  ADD KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_orders_purchase_request_id_foreign` (`purchase_request_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_requests_pr_number_unique` (`pr_number`),
  ADD KEY `purchase_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `purchase_requests_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_requests_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_request_items_purchase_request_id_foreign` (`purchase_request_id`),
  ADD KEY `purchase_request_items_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_request_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movement_analyses`
--
ALTER TABLE `stock_movement_analyses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movement_analyses_item_id_foreign` (`item_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_products_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_products_item_id_foreign` (`item_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_customer_id_foreign` (`customer_id`),
  ADD KEY `transactions_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_details_item_id_foreign` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wholesale_prices`
--
ALTER TABLE `wholesale_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wholesale_prices_item_id_foreign` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absences`
--
ALTER TABLE `absences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory_batches`
--
ALTER TABLE `inventory_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory_records`
--
ALTER TABLE `inventory_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_settings`
--
ALTER TABLE `inventory_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item_supplier`
--
ALTER TABLE `item_supplier`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `marketplace_order_items`
--
ALTER TABLE `marketplace_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stock_movement_analyses`
--
ALTER TABLE `stock_movement_analyses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wholesale_prices`
--
ALTER TABLE `wholesale_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absences`
--
ALTER TABLE `absences`
  ADD CONSTRAINT `absences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD CONSTRAINT `goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD CONSTRAINT `goods_receipt_items_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipt_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_batches`
--
ALTER TABLE `inventory_batches`
  ADD CONSTRAINT `inventory_batches_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_goods_receipt_item_id_foreign` FOREIGN KEY (`goods_receipt_item_id`) REFERENCES `goods_receipt_items` (`id`);

--
-- Constraints for table `inventory_records`
--
ALTER TABLE `inventory_records`
  ADD CONSTRAINT `inventory_records_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_supplier`
--
ALTER TABLE `item_supplier`
  ADD CONSTRAINT `item_supplier_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_supplier_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  ADD CONSTRAINT `marketplace_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace_order_items`
--
ALTER TABLE `marketplace_order_items`
  ADD CONSTRAINT `marketplace_order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `marketplace_order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `marketplace_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD CONSTRAINT `purchase_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_requests_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD CONSTRAINT `purchase_request_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_request_items_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stock_movement_analyses`
--
ALTER TABLE `stock_movement_analyses`
  ADD CONSTRAINT `stock_movement_analyses_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD CONSTRAINT `supplier_products_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `supplier_products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wholesale_prices`
--
ALTER TABLE `wholesale_prices`
  ADD CONSTRAINT `wholesale_prices_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
