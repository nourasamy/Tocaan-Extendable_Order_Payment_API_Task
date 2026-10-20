-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 12:52 PM
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
-- Database: `tocaan_task`
--

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
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', '1234567890', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(2, 'Jane Smith', '0987654321', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(3, 'Michael Johnson', '1122334455', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(4, 'Emily Williams', '5544332211', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(5, 'David Brown', '9876543210', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(6, 'Sarah Davis', '1357924680', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(7, 'James Miller', '2468013579', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(8, 'Jennifer Wilson', '3691234567', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(9, 'Robert Moore', '7654321098', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(10, 'Lisa Taylor', '1029384756', '2026-02-03 09:52:20', '2026-02-03 09:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Laptop', 999.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(2, 'Monitor', 299.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(3, 'Keyboard', 79.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(4, 'Mouse', 29.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(5, 'USB Cable', 9.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(6, 'Headphones', 149.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(7, 'Webcam', 59.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(8, 'External HDD', 89.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(9, 'SSD 500GB', 59.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(10, 'RAM 16GB', 129.99, '2026-02-03 09:52:20', '2026-02-03 09:52:20');

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
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_01_090642_create_clients_table', 1),
(5, '2026_02_01_090832_create_items_table', 1),
(6, '2026_02_01_091823_create_orders_table', 1),
(7, '2026_02_01_092732_create_order_details_table', 1),
(8, '2026_02_01_093715_create_payment_gateways_table', 1),
(9, '2026_02_01_094134_create_payments_table', 1),
(10, '2026_02_02_123140_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `grand_total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `gateway_id` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `handler_key` varchar(255) NOT NULL,
  `handler_class` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `client_id` varchar(255) DEFAULT NULL,
  `secret_key` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateways`
--

INSERT INTO `payment_gateways` (`id`, `name`, `handler_key`, `handler_class`, `active`, `client_id`, `secret_key`, `created_at`, `updated_at`) VALUES
(1, 'Credit Card', 'credit_card', 'App\\Services\\Gateways\\CreditCardGateway', 1, 'credit_card_client_id', 'credit_card_secret_key', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(2, 'PayPal', 'paypal', 'App\\Services\\Gateways\\PayPalGateway', 1, 'paypal_client_id', 'paypal_secret_key', '2026-02-03 09:52:20', '2026-02-03 09:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Kailyn Grant', 'filiberto.kertzmann@example.org', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', 'j1OHe11yQT', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(2, 'Mr. Lowell Welch', 'oschimmel@example.com', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', 'lcX3nEPViA', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(3, 'Adeline Bradtke', 'uhammes@example.net', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', 'vdXnvSy3ft', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(4, 'Terry Cummerata', 'yjones@example.com', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', 'ETzYUoESdy', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(5, 'Alda Morissette', 'qhand@example.com', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', '0M2w2Et1Bx', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(6, 'Emelia Hettinger', 'maia.bernier@example.com', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', 'BtHnC8FJ4j', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(7, 'Sheridan Kautzer', 'tokuneva@example.com', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', 'GSqHuhKF24', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(8, 'Mallie Heidenreich I', 'cpaucek@example.com', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', 'D4fhTtJEBu', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(9, 'Reymundo Ferry', 'dveum@example.org', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', 'svdmP4I1mX', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(10, 'Prof. Justyn Jacobs V', 'dariana24@example.org', '2026-02-03 09:52:20', '$2y$12$KYF4YFgBKaPJd6ZHyNhc8eNLkuNtQOAAcwrsrAz1ARSECTVzzy96a', 'bLJMYMCZRQ', '2026-02-03 09:52:20', '2026-02-03 09:52:20'),
(11, 'Admin User', 'admin@example.com', NULL, '$2y$12$03uvd41yKsgOP0zKA3HF8e6wgkoK6gD2brrU0uCLUcHrRXeqacqVC', NULL, '2026-02-03 09:52:20', '2026-02-03 09:52:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_phone_unique` (`phone`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_client_id_foreign` (`client_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_item_id_foreign` (`item_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_gateway_id_foreign` (`gateway_id`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_gateways_handler_key_unique` (`handler_key`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_gateway_id_foreign` FOREIGN KEY (`gateway_id`) REFERENCES `payment_gateways` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
