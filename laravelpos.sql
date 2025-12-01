-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 10:15 AM
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
  `id` int(20) UNSIGNED NOT NULL,
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
(83, 14, '02:23:00', NULL, '2025-11-09 19:23:10', '2025-11-09 19:23:10'),
(84, 14, '02:24:00', '02:26:00', '2025-11-09 19:24:21', '2025-11-09 19:26:51'),
(85, 14, '02:27:00', '02:41:00', '2025-11-09 19:27:04', '2025-11-09 19:41:23'),
(86, 12, '02:42:00', '02:54:00', '2025-11-09 19:42:01', '2025-11-09 19:54:14'),
(87, 12, '04:39:00', '04:43:00', '2025-11-09 21:39:37', '2025-11-09 21:43:02'),
(88, 12, '04:46:00', '04:48:00', '2025-11-09 21:46:38', '2025-11-09 21:48:02'),
(89, 14, '04:48:00', '04:52:00', '2025-11-09 21:48:42', '2025-11-09 21:52:06'),
(90, 12, '04:52:00', '04:54:00', '2025-11-09 21:52:16', '2025-11-09 21:54:12'),
(91, 17, '04:54:00', '05:06:00', '2025-11-09 21:54:22', '2025-11-09 22:06:56'),
(92, 12, '05:07:00', '05:19:00', '2025-11-09 22:07:21', '2025-11-09 22:19:43'),
(93, 14, '05:34:00', '05:35:00', '2025-11-09 22:34:24', '2025-11-09 22:35:32'),
(94, 12, '05:36:00', NULL, '2025-11-09 22:36:46', '2025-11-09 22:36:46'),
(95, 12, '20:28:00', '20:40:00', '2025-11-10 13:28:20', '2025-11-10 13:40:49'),
(96, 17, '20:41:00', '21:12:00', '2025-11-10 13:41:06', '2025-11-10 14:12:52'),
(97, 12, '21:12:00', '21:19:00', '2025-11-10 14:12:59', '2025-11-10 14:19:42'),
(98, 17, '21:19:00', '05:31:00', '2025-11-10 14:19:48', '2025-11-10 22:31:11'),
(99, 12, '05:31:00', '05:31:00', '2025-11-10 22:31:16', '2025-11-10 22:31:46'),
(100, 14, '05:31:00', '05:53:00', '2025-11-10 22:31:55', '2025-11-10 22:53:55'),
(101, 17, '05:53:00', '05:54:00', '2025-11-10 22:53:59', '2025-11-10 22:54:13'),
(102, 12, '05:54:00', '06:32:00', '2025-11-10 22:54:17', '2025-11-10 23:32:24'),
(103, 12, '06:32:00', NULL, '2025-11-10 23:32:30', '2025-11-10 23:32:30'),
(104, 12, '10:52:00', '11:07:00', '2025-11-11 03:52:09', '2025-11-11 04:07:06'),
(105, 14, '11:08:00', '11:10:00', '2025-11-11 04:08:32', '2025-11-11 04:10:45'),
(106, 12, '11:11:00', NULL, '2025-11-11 04:11:16', '2025-11-11 04:11:16'),
(107, 12, '22:46:00', '22:56:00', '2025-11-11 15:46:57', '2025-11-11 15:56:46'),
(108, 14, '00:21:00', '00:22:00', '2025-11-11 17:21:47', '2025-11-11 17:22:13'),
(109, 12, '22:38:00', '22:39:00', '2025-11-24 15:38:47', '2025-11-24 15:39:28'),
(110, 14, '22:52:00', '22:52:00', '2025-11-24 15:52:15', '2025-11-24 15:52:27'),
(111, 17, '22:52:00', NULL, '2025-11-24 15:52:31', '2025-11-24 15:52:31');

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
(21, 'Makanan dan Minuman', NULL, '2025-11-04 23:20:03', '2025-11-04 23:20:03'),
(22, 'Buku dan alat tulis', NULL, '2025-11-04 23:22:17', '2025-11-04 23:22:17'),
(23, 'Peralatan rumah tangga', NULL, '2025-11-04 23:22:33', '2025-11-04 23:22:33'),
(24, 'Kosmetik dan perawatan diri', NULL, '2025-11-04 23:22:48', '2025-11-04 23:22:48'),
(25, 'Kesehatan & Obat-Obatan Ringan', NULL, '2025-11-04 23:24:24', '2025-11-04 23:24:24');

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
  `status` varchar(255) DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `receipt_document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `expiry_status` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `type` enum('in','out','adjust') NOT NULL,
  `qty` decimal(12,2) NOT NULL,
  `ref_type` varchar(255) NOT NULL,
  `ref_id` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `cost_price` int(11) NOT NULL,
  `selling_price` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `picture` varchar(255) NOT NULL DEFAULT 'default.png',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `requires_expiry` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `code`, `category_id`, `cost_price`, `selling_price`, `stock`, `picture`, `created_at`, `updated_at`, `requires_expiry`) VALUES
(311, 'Oasis Air Minum 1500 mL', 'BLPP9581', 21, 5000, 7000, 0, '1762468998.png', '2025-11-06 15:43:18', '2025-11-09 19:40:54', 0),
(312, 'Oasis Air Minum Box of 600 mL', 'DBAT0940', 21, 3000, 4000, 35, '1762485461.png', '2025-11-06 20:17:41', '2025-11-10 15:15:05', 0),
(313, 'Sunlight Sabun Cuci Piring Sunlight Lime 650ml', 'BMMT0482', 23, 10000, 12000, 34, '1761083578.png', '2025-10-21 14:53:00', '2025-11-24 16:04:34', 0),
(314, 'Batere ABC Alkaline Uk AAA', 'QPKT9114', 23, 20000, 22000, 78, '1761083638.jpeg', '2025-10-21 14:53:58', '2025-11-24 15:53:31', 0),
(315, 'Stopmap kertas Folio tipe 5001', 'XNCT8114', 22, 1000, 2000, 85, '1761083666.jpg', '2025-10-21 14:54:26', '2025-11-24 16:33:41', 0);

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
(3, 15, 'PO-E9F2VKG8', 'completed', 'abuya', '123122323221', 'ambil jam 2', 14000.00, '2025-11-06 18:24:46', '2025-11-06 18:26:48'),
(4, 16, 'PO-EXBKUK7I', 'completed', 'berlian permata suci', '089541064646', 'terima', 28000.00, '2025-11-07 15:48:03', '2025-11-07 16:08:17'),
(5, 15, 'PO-LTE3QTG4', 'completed', 'abuya', '9684654416', NULL, 18000.00, '2025-11-07 16:33:13', '2025-11-09 17:21:48'),
(6, 15, 'PO-AGGFOLZE', 'completed', 'abuya', '089571239923', NULL, 28000.00, '2025-11-09 17:19:17', '2025-11-09 17:21:51'),
(7, 19, 'PO-OMH3F0B7', 'completed', 'farhan', '089553234298', 'terima', 2000.00, '2025-11-09 18:52:22', '2025-11-09 19:16:21'),
(8, 15, 'PO-DYGLFGZL', 'completed', 'abuya', '0895701233483', 'ambil bisa sekarang', 48000.00, '2025-11-09 19:17:57', '2025-11-09 19:18:30'),
(9, 16, 'PO-S7Z9MTCR', 'completed', 'berlian permata suci', '0895565456456', 'sasdsa', 102000.00, '2025-11-09 19:22:57', '2025-11-09 19:46:33'),
(10, 19, 'PO-ZIEKWUQL', 'completed', 'farhan', '2212222', NULL, 2000.00, '2025-11-09 19:41:50', '2025-11-10 13:29:02'),
(11, 15, 'PO-LVNANHRV', 'completed', 'abuya', '9878756674654', 'hjbgjhfkytgjhk', 48000.00, '2025-11-09 22:33:21', '2025-11-09 22:35:06'),
(12, 15, 'PO-VH3ZYHFS', 'completed', 'abuya', '0892732737', NULL, 7000.00, '2025-11-11 04:08:05', '2025-11-11 04:09:07');

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
(6, 3, 311, 2, 7000.00, '2025-11-06 18:24:46', '2025-11-06 18:24:46'),
(7, 4, 314, 1, 22000.00, '2025-11-07 15:48:03', '2025-11-07 15:48:03'),
(8, 4, 315, 1, 2000.00, '2025-11-07 15:48:03', '2025-11-07 15:48:03'),
(9, 4, 312, 1, 4000.00, '2025-11-07 15:48:03', '2025-11-07 15:48:03'),
(10, 5, 312, 1, 4000.00, '2025-11-07 16:33:13', '2025-11-07 16:33:13'),
(11, 5, 315, 1, 2000.00, '2025-11-07 16:33:13', '2025-11-07 16:33:13'),
(12, 5, 313, 1, 12000.00, '2025-11-07 16:33:13', '2025-11-07 16:33:13'),
(13, 6, 312, 1, 4000.00, '2025-11-09 17:19:17', '2025-11-09 17:19:17'),
(14, 6, 315, 1, 2000.00, '2025-11-09 17:19:17', '2025-11-09 17:19:17'),
(15, 6, 314, 1, 22000.00, '2025-11-09 17:19:17', '2025-11-09 17:19:17'),
(16, 7, 315, 1, 2000.00, '2025-11-09 18:52:22', '2025-11-09 18:52:22'),
(17, 8, 312, 1, 4000.00, '2025-11-09 19:17:57', '2025-11-09 19:17:57'),
(18, 8, 314, 2, 22000.00, '2025-11-09 19:17:57', '2025-11-09 19:17:57'),
(19, 9, 314, 4, 22000.00, '2025-11-09 19:22:57', '2025-11-09 19:22:57'),
(20, 9, 313, 1, 12000.00, '2025-11-09 19:22:57', '2025-11-09 19:22:57'),
(21, 9, 315, 1, 2000.00, '2025-11-09 19:22:57', '2025-11-09 19:22:57'),
(22, 10, 315, 1, 2000.00, '2025-11-09 19:41:50', '2025-11-09 19:41:50'),
(23, 11, 315, 2, 2000.00, '2025-11-09 22:33:21', '2025-11-09 22:33:21'),
(24, 11, 314, 2, 22000.00, '2025-11-09 22:33:21', '2025-11-09 22:33:21'),
(25, 12, 311, 1, 7000.00, '2025-11-11 04:08:05', '2025-11-11 04:08:05');

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
(1, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
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
(17, '2025_07_23_145713_create_purchase_orders_table', 1),
(18, '2025_07_23_145728_create_purchase_order_items_table', 1),
(19, '2025_09_03_000000_add_customer_role_and_contact_to_users_table', 1),
(20, '2025_09_04_000001_create_marketplace_orders_table', 1),
(21, '2025_09_04_000002_create_marketplace_order_items_table', 1),
(22, '2025_10_08_010229_add_online_fields_to_transactions_table', 1),
(23, '2025_10_13_000001_create_purchase_requests_table', 1),
(24, '2025_10_13_000002_create_purchase_request_items_table', 1),
(25, '2025_10_13_000004_create_goods_receipts_table', 1),
(26, '2025_10_13_000005_create_goods_receipt_items_table', 1),
(27, '2025_10_13_000006_create_invoices_table', 1),
(28, '2025_10_23_000007_add_required_columns_to_purchase_orders_and_items', 1),
(29, '2025_10_23_000008_make_purchase_order_items_item_id_nullable', 1),
(30, '2025_10_27_000001_add_username_and_picture_to_users', 1),
(31, '2025_11_05_234144_remove_unused_columns_from_goods_receipts', 2),
(32, '2025_11_05_234855_create_inventory_records_table', 3),
(33, '2025_11_06_000001_add_expiry_columns_to_goods_receipt_items', 4),
(34, '0001_01_01_000000_create_items_table', 5),
(35, '2025_11_07_232719_create_cart_items_table', 6),
(36, '0001_01_01_000002_create_inventory_movements_table', 7),
(37, '2025_11_07_000001_modify_item_id_in_goods_receipt_items', 8),
(38, '2025_11_07_231925_create_cart_items_table', 9),
(39, '0001_01_01_000001_create_cache_table', 10),
(40, '2025_11_11_224914_create_password_reset_tokens_table', 11);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('tumbalone12556@gmail.com', '$2y$12$EeIhVsi1dimKO0pbTBr/MOSJZ./Lonlyru5.OG1FNmyEJoxF/71Yu', '2025-11-11 17:16:46'),
('tumbalone12555@gmail.com', '$2y$12$ZRGEeAfRCn2tnmqGN36UWeptymaFV8MTEo7SzSWjVwnkXqhEi41Mu', '2025-11-11 17:33:23');

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
(1, 'Tunai', NULL, '2025-11-03 19:01:38', '2025-11-03 19:01:38'),
(4, 'Transfer', NULL, '2025-11-03 19:01:38', '2025-11-03 19:01:38'),
(5, 'OVO', NULL, '2025-11-03 19:01:38', '2025-11-03 19:01:38'),
(8, 'QRIS', NULL, '2025-11-03 19:01:38', '2025-11-03 19:01:38');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `po_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `supplier_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `supplier_notes` text DEFAULT NULL,
  `invoice_image_path` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `prices_confirmed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_number`, `supplier_id`, `purchase_request_id`, `po_date`, `status`, `total_amount`, `supplier_confirmed`, `supplier_notes`, `invoice_image_path`, `created_by`, `created_at`, `updated_at`, `prices_confirmed`) VALUES
(37, 'PO/2025/11/0001', 29, 33, '2025-11-11', 'sent', 200000.00, 0, NULL, NULL, 14, '2025-11-10 22:32:07', '2025-11-10 22:40:53', 1),
(38, 'PO/2025/11/0002', 27, 34, '2025-11-11', 'completed', 110000.00, 0, NULL, NULL, 12, '2025-11-10 22:55:07', '2025-11-10 22:56:02', 1),
(39, 'PO/2025/11/0003', 31, 35, '2025-11-11', 'sent', 100000.00, 0, NULL, NULL, 12, '2025-11-11 03:59:57', '2025-11-11 04:02:39', 1);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `item_id`, `product_name`, `quantity`, `unit`, `unit_price`, `notes`, `created_at`, `updated_at`) VALUES
(74, 30, NULL, 'Oasis Air Minum Box of 330 mL', 10, 'box', 15000.00, NULL, '2025-11-06 17:09:57', '2025-11-06 17:10:32'),
(75, 30, NULL, 'Oasis Air Minum Box of 240 mL', 5, 'box', 17000.00, NULL, '2025-11-06 17:09:57', '2025-11-06 17:10:32'),
(76, 30, NULL, 'Oasis Air Minum Box of 600 mL', 5, 'box', 25000.00, NULL, '2025-11-06 17:09:57', '2025-11-06 17:10:32'),
(77, 31, NULL, 'Cairan Pembersih Lantai Wipol 750ML', 55, 'pcs', 7000.00, NULL, '2025-11-06 19:10:37', '2025-11-06 19:14:02'),
(78, 31, NULL, 'Cairan Pembersih Lantai Porselen Vixal 750M', 55, 'pcs', 8000.00, NULL, '2025-11-06 19:10:37', '2025-11-06 19:14:02'),
(79, 32, NULL, 'Isi Stapler No. 10 Kangaro', 15, 'pcs', 20000.00, NULL, '2025-11-06 19:10:58', '2025-11-06 19:11:40'),
(80, 32, NULL, 'clip box binder clip 200 joyko', 22, 'pcs', 25000.00, NULL, '2025-11-06 19:10:58', '2025-11-06 19:11:40'),
(81, 32, NULL, 'Pulpen dan Pensil Standard ST 009', 25, 'box', 30000.00, NULL, '2025-11-06 19:10:58', '2025-11-06 19:11:40'),
(82, 33, NULL, 'Batere ABC Alkaline Uk AA', 20, 'pcs', 15000.00, NULL, '2025-11-06 20:34:08', '2025-11-06 20:34:30'),
(83, 33, NULL, 'Batere ABC Alkaline Uk AAA', 20, 'box', 20000.00, NULL, '2025-11-06 20:34:08', '2025-11-06 20:34:30'),
(84, 33, NULL, 'Post It Sign Here (Arrow) Tom dan Jerry', 10, 'box', 10000.00, NULL, '2025-11-06 20:34:08', '2025-11-06 20:34:30'),
(85, 34, NULL, 'yougurt', 10, 'box', 12000.00, NULL, '2025-11-09 22:09:24', '2025-11-09 22:10:22'),
(86, 35, NULL, 'Sunlight Sabun Cuci Piring Sunlight Lime 650ml', 10, 'pcs', 20000.00, NULL, '2025-11-10 13:41:29', '2025-11-10 13:42:33'),
(87, 36, NULL, 'Post It Sign Here (Arrow) Tom dan Jerry', 20, 'pcs', 20000.00, NULL, '2025-11-10 14:20:04', '2025-11-10 14:20:26'),
(88, 36, NULL, 'Ballpoint K-1 Kenko 1 Pack', 50, 'box', 50000.00, NULL, '2025-11-10 14:20:04', '2025-11-10 14:20:26'),
(89, 37, NULL, 'Note BANTEX FLEXI TAB 5 NEON COLOUR', 15, 'pcs', 5000.00, NULL, '2025-11-10 22:32:07', '2025-11-10 22:40:42'),
(90, 37, NULL, 'Lem Stick UHU 8 ram', 25, 'box', 5000.00, NULL, '2025-11-10 22:32:07', '2025-11-10 22:40:42'),
(91, 38, NULL, 'Cairan Pembersih Lantai Wipol 750ML', 5, 'box', 10000.00, NULL, '2025-11-10 22:55:07', '2025-11-10 22:55:22'),
(92, 38, NULL, 'Cairan Pembersih Lantai Porselen Vixal 750M', 5, 'box', 12000.00, NULL, '2025-11-10 22:55:07', '2025-11-10 22:55:22'),
(93, 39, NULL, 'KERTAS HVS', 10, 'box', 10000.00, NULL, '2025-11-11 03:59:57', '2025-11-11 04:02:24');

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
(33, 'PR-0001/11/2025', 17, '2025-11-11', 29, 'po_created', 12, '2025-11-10 22:31:40', 'approved', NULL, NULL, NULL, 0, NULL, '2025-11-10 22:29:26', '2025-11-10 22:32:07'),
(34, 'PR-0002/11/2025', 12, '2025-11-11', 27, 'po_created', 12, '2025-11-10 22:54:56', 'approved', NULL, NULL, NULL, 0, NULL, '2025-11-10 22:54:49', '2025-11-10 22:55:07'),
(35, 'PR-0003/11/2025', 12, '2025-11-11', 31, 'po_created', 12, '2025-11-11 03:59:14', 'approved', NULL, NULL, NULL, 0, NULL, '2025-11-11 03:58:29', '2025-11-11 03:59:57');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_items`
--

CREATE TABLE `purchase_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_request_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) DEFAULT 'pcs',
  `current_stock` int(11) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_request_items`
--

INSERT INTO `purchase_request_items` (`id`, `purchase_request_id`, `product_name`, `quantity`, `unit`, `current_stock`, `notes`, `created_at`, `updated_at`) VALUES
(77, 33, 'Note BANTEX FLEXI TAB 5 NEON COLOUR', 15, 'pcs', 0, NULL, '2025-11-10 22:29:26', '2025-11-10 22:29:26'),
(78, 33, 'Lem Stick UHU 8 ram', 25, 'box', 0, NULL, '2025-11-10 22:29:26', '2025-11-10 22:29:26'),
(79, 34, 'Cairan Pembersih Lantai Wipol 750ML', 5, 'box', 0, NULL, '2025-11-10 22:54:49', '2025-11-10 22:54:49'),
(80, 34, 'Cairan Pembersih Lantai Porselen Vixal 750M', 5, 'box', 0, NULL, '2025-11-10 22:54:49', '2025-11-10 22:54:49'),
(81, 35, 'KERTAS HVS', 10, 'box', 0, NULL, '2025-11-11 03:58:29', '2025-11-11 03:58:29');

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
('JlzJHiKRyhiZ2evxZMj5nUpsbWHuTWIS2tDgZede', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQjdIWHhqalNadHFiaG5ua3BFVjRaS0VzdkgxeWROU0lyVVNVS0UxaCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO31zOjIyOiJQSFBERUJVR0JBUl9TVEFDS19EQVRBIjthOjA6e319', 1762882566),
('oIDW2TRdM524I2pJ95KDtWrJavGFZ99W3Rv2r0TB', 17, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNXBjZklIc29iWTRYZW10b0xkeE9pUjJGeHJ0TGNsejRpSjBIRjBTeCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbW9udGhseS1pbmNvbWU/eWVhcj0yMDI1Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTc7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9fQ==', 1764002049),
('t1OhJSgd9uCo7ZrTpCFa1u9KZ0SLwN3z7kWszzHi', 20, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVGtIaDhpSHhISmo4ZUtrUml6aFVtZUt4R3NVZEdDYUp6Z3hQa1YybiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMDt9', 1762882971);

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
(26, 'PT. Oasis Waters International', '0711432446', 'Jl. Kantor Lurah Lorong Anggrek RT 21 RW 04 Sukomoro Km. 18, Lawang Kidul, Kec. Ilir Tim. II, Kab. Banyuasin, Sumatera Selatan 30961', NULL, 'Pemesanan Barang Melalui Website resmi https://id1908657-pt-oasis-waters-international.contact.page/#google_vignette', '2025-11-04 23:28:32', '2025-11-04 23:28:32'),
(27, 'PT. Delta Muster Rise', '08125523455', 'Jl. Pangeran Ayin Komp. Sako Permai Blok E No. 5, Kel. Sako Baru Palembang, Sumatera Selatan, Indonesia', NULL, 'Barang di pesan Melalui website https://www.indotrading.com/deltamusterrise', '2025-11-04 23:32:33', '2025-11-04 23:32:57'),
(28, 'PT. Surya Vesakha Pamungkas', '0892444434544', 'Jl. Sei Itam No. 77 Kelurahan Bukit Lama Kecamatan Ilir Barat I Palembang, Sumatera Selatan, Indonesia', NULL, 'Produk dipesan melalui website resmi https://www.indotrading.com/suryavesakhapamungkas', '2025-11-04 23:42:45', '2025-11-04 23:42:45'),
(29, 'CV. Dempo Center', '089232555222', 'Jl. Sultan M Mansyur Toko Dempo Laundry, Bukit Lama, Ilir Barat 1, Kota palembang, Palembang, Sumatera Selatan', NULL, 'Produk dipesan melalui website resmi https://www.indotrading.com/dempocenter', '2025-11-04 23:47:54', '2025-11-04 23:47:54'),
(30, 'pt.alif12', '0882323222', 'gandus', 'ptalif@gmail.com', NULL, '2025-11-09 22:04:25', '2025-11-09 22:04:25'),
(31, 'PT. KONTAK ABADI', '08921232322', 'GANDU', 'SQMD@GMAIL.COM', NULL, '2025-11-11 03:57:31', '2025-11-11 03:57:31');

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
(1, 26, NULL, 'Oasis Air Minum 240 mL', NULL, 1, NULL, '2025-11-04 23:28:32', '2025-11-04 23:28:32'),
(2, 26, NULL, 'Oasis Air Minum 300 mL', NULL, 1, NULL, '2025-11-04 23:28:32', '2025-11-04 23:28:32'),
(3, 26, NULL, 'Oasis Air Minum 600 mL', NULL, 1, NULL, '2025-11-04 23:28:32', '2025-11-04 23:28:32'),
(4, 26, NULL, 'Oasis Air Minum 1500 mL', NULL, 1, NULL, '2025-11-04 23:28:32', '2025-11-04 23:28:32'),
(5, 26, NULL, 'Oasis Air Minum Box of 330 mL', NULL, 1, NULL, '2025-11-04 23:28:32', '2025-11-04 23:28:32'),
(6, 26, NULL, 'Oasis Air Minum Box of 240 mL', NULL, 1, NULL, '2025-11-04 23:28:32', '2025-11-04 23:28:32'),
(7, 26, NULL, 'Oasis Air Minum Box of 600 mL', NULL, 1, NULL, '2025-11-04 23:28:32', '2025-11-04 23:28:32'),
(8, 26, NULL, 'Oasis Air Minum Box of 1500 mL', NULL, 1, NULL, '2025-11-04 23:28:32', '2025-11-04 23:28:32'),
(12, 27, NULL, 'Cairan Pembersih Lantai Porselen Vixal 750M', NULL, 1, NULL, '2025-11-04 23:33:35', '2025-11-04 23:33:35'),
(13, 27, NULL, 'Cairan Pembersih Lantai Wipol 750ML', NULL, 1, NULL, '2025-11-04 23:33:35', '2025-11-04 23:33:35'),
(14, 27, NULL, 'Sunlight Sabun Cuci Piring Sunlight Lime 650ml', NULL, 1, NULL, '2025-11-04 23:33:35', '2025-11-04 23:33:35'),
(15, 28, NULL, 'Post It Sign Here (Arrow) Tom dan Jerry', NULL, 1, NULL, '2025-11-04 23:42:45', '2025-11-04 23:42:45'),
(16, 28, NULL, 'Stopmap Folio Diamond Tipe 5002', NULL, 1, NULL, '2025-11-04 23:42:45', '2025-11-04 23:42:45'),
(17, 28, NULL, 'Pensil 2B Faber Castell 1 Pack', NULL, 1, NULL, '2025-11-04 23:42:45', '2025-11-04 23:42:45'),
(18, 28, NULL, 'Ballpoint K-1 Kenko 1 Pack', NULL, 1, NULL, '2025-11-04 23:42:45', '2025-11-04 23:42:45'),
(19, 28, NULL, 'Batere ABC Alkaline Uk AAA', NULL, 1, NULL, '2025-11-04 23:42:45', '2025-11-04 23:42:45'),
(20, 28, NULL, 'Batere ABC Alkaline Uk AA', NULL, 1, NULL, '2025-11-04 23:42:45', '2025-11-04 23:42:45'),
(21, 28, NULL, 'Stopmap kertas Folio tipe 5001', NULL, 1, NULL, '2025-11-04 23:42:45', '2025-11-04 23:42:45'),
(22, 28, NULL, 'Binder Clips Tipe 105 Kenko', NULL, 1, NULL, '2025-11-04 23:42:45', '2025-11-04 23:42:45'),
(33, 29, NULL, 'Note BANTEX FLEXI TAB 5 NEON COLOUR', NULL, 1, NULL, '2025-11-07 14:01:24', '2025-11-07 14:01:24'),
(34, 29, NULL, 'Lem Stick UHU 8 ram', NULL, 1, NULL, '2025-11-07 14:01:24', '2025-11-07 14:01:24'),
(35, 29, NULL, 'Lem Stick UHU 21 gram', NULL, 1, NULL, '2025-11-07 14:01:24', '2025-11-07 14:01:24'),
(36, 29, NULL, 'penghapus faber castell Putih KECIL 1872', NULL, 1, NULL, '2025-11-07 14:01:24', '2025-11-07 14:01:24'),
(37, 29, NULL, 'Spidol dan Highlighter spidol whiteboard snowman', NULL, 1, NULL, '2025-11-07 14:01:24', '2025-11-07 14:01:24'),
(38, 30, NULL, 'yougurt', NULL, 1, NULL, '2025-11-09 22:04:25', '2025-11-09 22:04:25'),
(39, 30, NULL, 'susu milk', NULL, 1, NULL, '2025-11-09 22:04:25', '2025-11-09 22:04:25'),
(40, 31, NULL, 'KERTAS HVS', NULL, 1, NULL, '2025-11-11 03:57:31', '2025-11-11 03:57:31'),
(41, 31, NULL, 'KERTAS A4', NULL, 1, NULL, '2025-11-11 03:57:31', '2025-11-11 03:57:31');

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
(19, 12, NULL, '0711250001', '1', 105000, 0, 1, NULL, 105000, 0, 'paid', NULL, NULL, NULL, '(diproses: syafiq Muhammad Alif, 07/11/2025 00:06)', '2025-11-06 17:06:29', '2025-11-06 17:06:29'),
(22, 12, NULL, '0711250002', '2', 105000, 0, 1, NULL, 105000, 0, 'paid', NULL, NULL, NULL, '(diproses: syafiq Muhammad Alif, 07/11/2025 01:21)', '2025-11-06 18:21:10', '2025-11-06 18:21:11'),
(23, 14, NULL, '0711250003', '3', 14000, 0, 1, 'online', 14000, 0, 'paid', 'paid', 'picked_up', 'PO-E9F2VKG8', 'Marketplace pickup: abuya (123122323221)', '2025-11-06 18:26:48', '2025-11-06 18:26:48'),
(25, 12, NULL, '0711250005', '5', 60000, 0, 1, NULL, 60000, 0, 'paid', NULL, NULL, NULL, '(diproses: syafiq Muhammad Alif, 07/11/2025 21:04)', '2025-11-07 14:04:52', '2025-11-07 14:04:52'),
(28, 12, NULL, '0711250006', '6', 34000, 0, 1, NULL, 34000, 0, 'paid', NULL, NULL, NULL, '(diproses: syafiq Muhammad Alif, 07/11/2025 21:27)', '2025-11-07 14:27:13', '2025-11-07 14:27:13'),
(34, 12, NULL, '2108250002', '2', 12000, 0, 1, NULL, 12000, 0, 'paid', NULL, NULL, NULL, '(diproses: syafiq Muhammad Alif, 21/08/2025 22:25)', '2025-08-21 15:25:24', '2025-08-21 15:25:24'),
(35, 12, NULL, '2108250002', '2', 8000, 0, 1, NULL, 8000, 0, 'paid', NULL, NULL, NULL, '(diproses: syafiq Muhammad Alif, 21/08/2025 22:25)', '2025-08-21 15:25:56', '2025-08-21 15:25:56'),
(36, 12, NULL, '2108250003', '3', 22000, 0, 1, NULL, 22000, 0, 'paid', NULL, NULL, NULL, '(diproses: syafiq Muhammad Alif, 21/08/2025 22:26)', '2025-08-21 15:26:16', '2025-08-21 15:26:16'),
(38, 14, NULL, '0711250008', '8', 28000, 0, 1, 'online', 28000, 0, 'paid', 'paid', 'picked_up', 'PO-EXBKUK7I', 'Marketplace pickup: berlian permata suci (089541064646)', '2025-11-07 16:08:17', '2025-11-07 16:08:17'),
(39, 14, NULL, '1011250001', '1', 18000, 0, 1, 'online', 18000, 0, 'paid', 'paid', 'picked_up', 'PO-LTE3QTG4', 'Marketplace pickup: abuya (9684654416)', '2025-11-09 17:21:48', '2025-11-09 17:21:48'),
(40, 14, NULL, '1011250002', '2', 28000, 0, 1, 'online', 28000, 0, 'paid', 'paid', 'picked_up', 'PO-AGGFOLZE', 'Marketplace pickup: abuya (089571239923)', '2025-11-09 17:21:51', '2025-11-09 17:21:51'),
(49, 12, NULL, '1011250010', '10', 22000, 0, 1, NULL, 22000, 0, 'paid', NULL, NULL, NULL, '(diproses: syafiq Muhammad Alif, 10/11/2025 20:33)', '2025-11-10 13:33:49', '2025-11-10 13:33:49'),
(50, 17, NULL, '1011250008', '8', 10000, 0, 1, NULL, 10000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 10/11/2025 22:07)', '2025-11-10 15:07:56', '2025-11-10 15:07:56'),
(51, 17, NULL, '1011250009', '9', 2000, 0, 1, NULL, 2000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 10/11/2025 22:14)', '2025-11-10 15:14:35', '2025-11-10 15:14:35'),
(52, 17, NULL, '1011250010', '10', 152000, 0, 1, NULL, 152000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 10/11/2025 22:15)', '2025-11-10 15:15:05', '2025-11-10 15:15:05'),
(53, 17, NULL, '1011250010', '10', 22000, 0, 1, NULL, 22000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 10/11/2025 22:18)', '2025-11-10 15:18:06', '2025-11-10 15:18:06'),
(54, 17, NULL, '1011250010', '10', 66000, 0, 1, NULL, 66000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 10/11/2025 22:22)', '2025-11-10 15:22:34', '2025-11-10 15:22:34'),
(55, 17, NULL, '1011250010', '10', 12000, 0, 1, NULL, 12000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 10/11/2025 22:26)', '2025-11-10 15:26:56', '2025-11-10 15:26:56'),
(56, 17, NULL, '1111250001', '1', 2000, 0, 1, NULL, 2000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 11/11/2025 05:28)', '2025-11-10 22:28:33', '2025-11-10 22:28:33'),
(57, 15, NULL, '1111250002', '2', 7000, 0, 1, 'online', 7000, 0, 'paid', 'paid', 'picked_up', 'PO-VH3ZYHFS', 'Marketplace pickup: abuya (0892732737)', '2025-11-11 04:09:07', '2025-11-11 04:09:07'),
(58, 17, NULL, '2411250001', '1', 110000, 0, 1, NULL, 110000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 24/11/2025 22:53)', '2025-11-24 15:53:31', '2025-11-24 15:53:31'),
(59, 17, NULL, '2411250002', '2', 12000, 0, 1, NULL, 12000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 24/11/2025 23:04)', '2025-11-24 16:04:34', '2025-11-24 16:04:34'),
(60, 17, NULL, '2411250003', '3', 10000, 0, 1, NULL, 10000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 24/11/2025 23:11)', '2025-11-24 16:11:26', '2025-11-24 16:11:27'),
(61, 17, NULL, '2411250004', '4', 22000, 0, 1, NULL, 22000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 24/11/2025 23:17)', '2025-11-24 16:17:13', '2025-11-24 16:17:13'),
(62, 17, NULL, '2411250005', '5', 2000, 0, 1, NULL, 2000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 24/11/2025 23:20)', '2025-11-24 16:20:47', '2025-11-24 16:20:47'),
(63, 17, NULL, '2411250006', '6', 30000, 0, 1, NULL, 30000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 24/11/2025 23:30)', '2025-11-24 16:30:49', '2025-11-24 16:30:49'),
(64, 17, NULL, '2411250007', '7', 2000, 0, 1, NULL, 2000, 0, 'paid', NULL, NULL, NULL, '(diproses: jihan kirana, 24/11/2025 23:33)', '2025-11-24 16:33:41', '2025-11-24 16:33:41');

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
(10, 19, 311, 15, 7000, 105000, '2025-11-06 17:06:29', '2025-11-06 17:06:29'),
(11, 22, 311, 15, 7000, 105000, '2025-11-06 18:21:11', '2025-11-06 18:21:11'),
(12, 23, 311, 2, 7000, 14000, '2025-11-06 18:26:48', '2025-11-06 18:26:48'),
(15, 25, 312, 15, 4000, 60000, '2025-11-07 14:04:52', '2025-11-07 14:04:52'),
(16, 28, 311, 2, 7000, 14000, '2025-11-07 14:27:13', '2025-11-07 14:27:13'),
(17, 28, 312, 5, 4000, 20000, '2025-11-07 14:27:13', '2025-11-07 14:27:13'),
(25, 34, 313, 1, 12000, 12000, '2025-08-21 15:25:24', '2025-08-21 15:25:24'),
(26, 35, 315, 4, 2000, 8000, '2025-08-21 15:25:56', '2025-08-21 15:25:56'),
(27, 36, 314, 1, 22000, 22000, '2025-08-21 15:26:16', '2025-08-21 15:26:16'),
(29, 38, 314, 1, 22000, 22000, '2025-11-07 16:08:17', '2025-11-07 16:08:17'),
(30, 38, 315, 1, 2000, 2000, '2025-11-07 16:08:17', '2025-11-07 16:08:17'),
(31, 38, 312, 1, 4000, 4000, '2025-11-07 16:08:17', '2025-11-07 16:08:17'),
(32, 39, 312, 1, 4000, 4000, '2025-11-09 17:21:48', '2025-11-09 17:21:48'),
(33, 39, 315, 1, 2000, 2000, '2025-11-09 17:21:48', '2025-11-09 17:21:48'),
(34, 39, 313, 1, 12000, 12000, '2025-11-09 17:21:48', '2025-11-09 17:21:48'),
(35, 40, 312, 1, 4000, 4000, '2025-11-09 17:21:51', '2025-11-09 17:21:51'),
(36, 40, 315, 1, 2000, 2000, '2025-11-09 17:21:51', '2025-11-09 17:21:51'),
(37, 40, 314, 1, 22000, 22000, '2025-11-09 17:21:51', '2025-11-09 17:21:51'),
(50, 49, 314, 1, 22000, 22000, '2025-11-10 13:33:49', '2025-11-10 13:33:49'),
(51, 50, 315, 5, 2000, 10000, '2025-11-10 15:07:56', '2025-11-10 15:07:56'),
(52, 51, 315, 1, 2000, 2000, '2025-11-10 15:14:35', '2025-11-10 15:14:35'),
(53, 52, 313, 11, 12000, 132000, '2025-11-10 15:15:05', '2025-11-10 15:15:05'),
(54, 52, 312, 5, 4000, 20000, '2025-11-10 15:15:05', '2025-11-10 15:15:05'),
(55, 53, 315, 11, 2000, 22000, '2025-11-10 15:18:06', '2025-11-10 15:18:06'),
(56, 54, 314, 3, 22000, 66000, '2025-11-10 15:22:34', '2025-11-10 15:22:34'),
(57, 55, 313, 1, 12000, 12000, '2025-11-10 15:26:56', '2025-11-10 15:26:56'),
(58, 56, 315, 1, 2000, 2000, '2025-11-10 22:28:33', '2025-11-10 22:28:33'),
(59, 57, 311, 1, 7000, 7000, '2025-11-11 04:09:07', '2025-11-11 04:09:07'),
(60, 58, 314, 5, 22000, 110000, '2025-11-24 15:53:31', '2025-11-24 15:53:31'),
(61, 59, 313, 1, 12000, 12000, '2025-11-24 16:04:34', '2025-11-24 16:04:34'),
(62, 60, 315, 5, 2000, 10000, '2025-11-24 16:11:27', '2025-11-24 16:11:27'),
(63, 61, 315, 11, 2000, 22000, '2025-11-24 16:17:13', '2025-11-24 16:17:13'),
(64, 62, 315, 1, 2000, 2000, '2025-11-24 16:20:47', '2025-11-24 16:20:47'),
(65, 63, 315, 15, 2000, 30000, '2025-11-24 16:30:49', '2025-11-24 16:30:49'),
(66, 64, 315, 1, 2000, 2000, '2025-11-24 16:33:41', '2025-11-24 16:33:41');

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `address`, `email_verified_at`, `password`, `role`, `position`, `picture`, `remember_token`, `created_at`, `updated_at`, `is_active`) VALUES
(11, 'Admin', 'admin', 'admin@example.com', NULL, NULL, NULL, '$2y$12$Fsrtrf5lhPBp7bvFsxlj5OYahk.W.5zPE0DWjqfrT/pO9563n3oJe', 'owner', 'Owner', 'profile.jpg', NULL, '2025-11-03 19:01:38', '2025-11-03 19:01:38', 1),
(12, 'syafiq Muhammad Alif', 'syafiq', 'mhdalif12555@Gmail.com', '0895701033483', NULL, NULL, '$2y$12$h4BahVKKSHlJi7wq4z0KUe4cBLF/N66WBRMk0Q2XvcErxMO13u1I2', 'supervisor', 'Manager Operasional', '1762323210.jpg', NULL, '2025-11-04 23:13:30', '2025-11-04 23:13:30', 1),
(14, 'riski halimawan', 'riski', 'tumbalone12556@gmail.com', '089211223322', NULL, NULL, '$2y$12$/HzTkTGEjwgiMqGj0WSFN.DtK0NTIjYP8Q0cbrEFKKOQ/UcjbJUt6', 'cashier', 'Kasir', '1762323377.jpg', NULL, '2025-11-04 23:16:17', '2025-11-04 23:16:17', 1),
(15, 'abuya', 'abuya', 'tumbalone12555@gmail.com', '08923232', 'Jalan Griyas', NULL, '$2y$12$RFiVaRqqgXfbeZys.mAOleK1PXthq8nXdNj6ldj/I3WVJbAvs2rFa', 'customer', NULL, 'profile.jpg', 'LnMjuvJPx5FgQGGCc7Kholp8crKNUsLCmF22k6meVyOcNyxbDXyaWb6QKTMj', '2025-11-04 23:17:31', '2025-11-11 17:07:20', 1),
(16, 'berlian permata suci', 'berlian', 'tumbalone12554@gmail.com', '0892452222', 'perumahan mitra agung', NULL, '$2y$12$ZafATGobskfldVhykn9QV./0X49IN2c7YtU.V.prFZbK3WTRd.cKe', 'customer', NULL, 'profile.jpg', 'IS4HO56pzVOJUGCpQwJTyRruiVEYSoqvRIRLy2HjSN4UqxHbcc6f2SCYP8aK', '2025-11-04 23:18:04', '2025-11-11 16:38:37', 1),
(17, 'jihan kirana', 'jihank', 'tumbalone12553@gmail.com', '08989612983', NULL, NULL, '$2y$12$.cpWDLmSbsHyDGCHoW5qROOhF/qhdVzdg3Hdf0a6yIow8BRoqbNvu', 'admin', 'Admin Gudang', '1762331727.jpg', NULL, '2025-11-05 01:35:27', '2025-11-05 01:35:27', 1),
(18, 'farhan', 'farhan', 'tumbalone12552@gmail.com', '089520944437', 'pakjo', NULL, '$2y$12$XdIQuqZ6hadRyOgxyV5cPu8oqQzz9J1PAedNXpzZkwoGqz1BnBpuC', 'customer', NULL, 'profile.jpg', NULL, '2025-11-09 17:39:03', '2025-11-09 18:20:10', 0),
(19, 'farhan', 'farhan12', 'tumbalone12551@gmail.com', '0893434323342', 'pakjo', NULL, '$2y$12$K3i3NfEhYBHyldn.EwPxM.4gWDutSeqijRVtJtFZGuxX0mRAJ1MJS', 'customer', NULL, 'profile.jpg', NULL, '2025-11-09 18:25:49', '2025-11-09 18:25:49', 1),
(20, 'hafiz', 'hafiz', 'tumbalone1255@gmail.com', '0895512212', 'gandus', NULL, '$2y$12$iVo8FFjSG3lc7R711r5bfu8XngkGruDSrMnTs5IMbLJQj/0iFiLJW', 'customer', NULL, 'profile.jpg', '4BbOgL63ccWECoZE0X6yWQ2xciymFCWT57aT600BXUN06gXeOL98vRbeNZ6n', '2025-11-09 21:44:52', '2025-11-11 17:42:45', 1),
(21, 'Syafiq Muhammad Alif', 'alif12', 'sq.ey115@gmail.com', '08957011111033483', 'jalan griya asri gandus bloook k', NULL, '$2y$12$2kQP3bwSKo1dkouQRSE3RuGYqzWT4pXw9S2Wis2CKvOVpVtuRqo6e', 'customer', NULL, 'profile.jpg', 'Z1Ik6OLUFDm57kx4PW5hc0te0scVRv1dkLSKPxui7MF4RmIbgo94RBaPm3vy', '2025-11-11 15:58:28', '2025-11-11 17:41:44', 1);

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
  ADD UNIQUE KEY `gr_number` (`gr_number`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `received_by` (`received_by`);

--
-- Indexes for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_id` (`goods_receipt_id`),
  ADD KEY `idx_lot_code` (`lot_code`),
  ADD KEY `idx_item_expiry` (`item_id`,`expiry_date`),
  ADD KEY `idx_batch_number` (`batch_number`);

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
  ADD KEY `idx_gr_item_type` (`goods_receipt_item_id`,`type`),
  ADD KEY `idx_reference` (`ref_type`,`ref_id`);

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
  ADD KEY `purchase_request_items_purchase_request_id_foreign` (`purchase_request_id`);

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
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `inventory_batches`
--
ALTER TABLE `inventory_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `inventory_records`
--
ALTER TABLE `inventory_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `inventory_settings`
--
ALTER TABLE `inventory_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT for table `item_supplier`
--
ALTER TABLE `item_supplier`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `marketplace_order_items`
--
ALTER TABLE `marketplace_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `stock_movement_analyses`
--
ALTER TABLE `stock_movement_analyses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `wholesale_prices`
--
ALTER TABLE `wholesale_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `goods_receipts_ibfk_1` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipts_ibfk_2` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD CONSTRAINT `goods_receipt_items_ibfk_1` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `fk_movement_gr_item` FOREIGN KEY (`goods_receipt_item_id`) REFERENCES `goods_receipt_items` (`id`);

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
  ADD CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE;

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
