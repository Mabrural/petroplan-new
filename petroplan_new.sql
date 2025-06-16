-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 16, 2025 at 06:03 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `petroplan_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

CREATE TABLE `document_types` (
  `id` bigint UNSIGNED NOT NULL,
  `document_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `document_types`
--

INSERT INTO `document_types` (`id`, `document_name`, `created_by`, `created_at`, `updated_at`) VALUES
(3, 'Foto Kapal / Nama Kapal', 2, '2025-06-15 21:14:10', '2025-06-15 21:14:10');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuels`
--

CREATE TABLE `fuels` (
  `id` bigint UNSIGNED NOT NULL,
  `fuel_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuels`
--

INSERT INTO `fuels` (`id`, `fuel_type`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'Biosolar', 2, '2025-06-13 01:12:48', '2025-06-13 01:12:48'),
(3, 'Pertadex', 2, '2025-06-13 01:13:06', '2025-06-13 01:13:06'),
(4, 'Dexlite', 2, '2025-06-13 01:15:01', '2025-06-13 01:15:01');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_05_041844_add_is_active_to_users_table', 2),
(5, '2025_06_05_044128_add_slug_to_users_table', 3),
(6, '2025_06_05_072009_create_personal_access_tokens_table', 3),
(7, '2025_06_05_074734_add_is_admin_to_users_table', 3),
(8, '2025_06_05_100817_create_role_permissions_table', 4),
(9, '2025_06_13_024815_create_periodes_table', 5),
(10, '2025_06_13_080034_create_fuels_table', 6),
(11, '2025_06_13_082027_create_vessels_table', 7),
(12, '2025_06_13_084725_create_termins_table', 8),
(13, '2025_06_13_091124_create_spks_table', 9),
(14, '2025_06_16_022612_create_shipments_table', 10),
(15, '2025_06_16_032638_create_document_types_table', 11),
(16, '2025_06_16_040504_create_upload_shipment_documents_table', 12),
(17, '2025_06_16_044124_add_period_id_to_upload_shipment_documents_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('mabrur@petrolindo.com', '$2y$12$8isPBdCQFC/u9fI8KSn6PORwJDB7UyNNFstj8HNFY5stW.OznhOrC', '2025-06-04 01:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `periodes`
--

CREATE TABLE `periodes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `periodes`
--

INSERT INTO `periodes` (`id`, `name`, `year`, `start_date`, `end_date`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(6, 'Periode 2025', '2025', '2025-01-01', '2025-12-31', 0, 2, '2025-06-12 20:30:42', '2025-06-15 18:49:06'),
(7, 'Periode 2026', '2026', '2026-01-01', '2026-12-31', 1, 2, '2025-06-15 19:47:46', '2025-06-15 19:47:46');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` char(36) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'd85a0986-cf31-44cf-b281-d3ce33dfafdf',
  `user_id` bigint UNSIGNED NOT NULL,
  `permission` enum('admin_officer','operasion') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `slug`, `user_id`, `permission`, `created_at`, `updated_at`) VALUES
(16, '02420958-f071-4e28-8472-9b05a19d1161', 19, 'operasion', '2025-06-12 19:22:12', '2025-06-12 19:22:12'),
(20, '83244e3c-994d-4ea3-bc0e-b093b6d6b286', 2, 'admin_officer', '2025-06-12 19:30:38', '2025-06-12 19:30:38'),
(22, '45f4632d-4394-4c1b-9970-9f6a3d0781b7', 21, 'operasion', '2025-06-13 00:05:11', '2025-06-13 00:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1hoyW3JCA8AHWFzx7KiKGBNjXg9I6Z0tyyXmYzCn', 2, '192.168.1.2', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQlVNV3lOcG1iYXFEeTU4MXZGdENFZFE4blRVU2VKR1VLSnkyMHVQTyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ5OiJodHRwOi8vMTkyLjE2OC4xLjY6ODAwMC91cGxvYWQtc2hpcG1lbnQtZG9jdW1lbnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1750053641),
('49UbjJNh6edix2Hj2HTDaJEisiynAwWNppsubRaj', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibEhsbEpGMENoU3BvMVM2bWllR1YzV0d1clpNMjdIdjZRSmYxQU44TyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvdXBsb2FkLXNoaXBtZW50LWRvY3VtZW50cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1750053649);

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `termin_id` bigint UNSIGNED NOT NULL,
  `shipment_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vessel_id` bigint UNSIGNED NOT NULL,
  `spk_id` bigint UNSIGNED NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fuel_id` bigint UNSIGNED NOT NULL,
  `volume` int NOT NULL,
  `p` int DEFAULT NULL,
  `a` int DEFAULT NULL,
  `b` int DEFAULT NULL,
  `completion_date` date NOT NULL,
  `lo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_shipment` enum('in_progress','cancelled','completed','filling_completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `period_id`, `termin_id`, `shipment_number`, `vessel_id`, `spk_id`, `location`, `fuel_id`, `volume`, `p`, `a`, `b`, `completion_date`, `lo`, `status_shipment`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 6, 4, '1', 4, 2, 'Der. Batam', 2, 25000, NULL, NULL, NULL, '2025-06-15', '-', 'in_progress', 2, '2025-06-15 19:50:42', '2025-06-15 19:50:42'),
(3, 7, 8, '2', 4, 2, 'Der. Batam', 2, 150000, NULL, NULL, NULL, '2025-06-16', '-', 'in_progress', 2, '2025-06-15 19:56:07', '2025-06-15 19:57:37');

-- --------------------------------------------------------

--
-- Table structure for table `spks`
--

CREATE TABLE `spks` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `spk_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spk_date` date NOT NULL,
  `spk_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spks`
--

INSERT INTO `spks` (`id`, `period_id`, `spk_number`, `spk_date`, `spk_file`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 6, 'B-161/OP.03.04/VI/2025', '2025-06-13', 'spk_files/lFMdrlEihoUZnoTD0BEHr6qVdM9OMUgf2JCXvDlh.pdf', 2, '2025-06-13 02:31:23', '2025-06-13 02:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `termins`
--

CREATE TABLE `termins` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `termin_number` int UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `termins`
--

INSERT INTO `termins` (`id`, `period_id`, `termin_number`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 6, 1, 2, '2025-06-13 02:00:52', '2025-06-13 02:01:30'),
(6, 6, 2, 2, '2025-06-15 19:48:12', '2025-06-15 19:48:12'),
(7, 6, 3, 2, '2025-06-15 19:48:19', '2025-06-15 19:48:19'),
(8, 7, 1, 2, '2025-06-15 19:48:25', '2025-06-15 19:48:25');

-- --------------------------------------------------------

--
-- Table structure for table `upload_shipment_documents`
--

CREATE TABLE `upload_shipment_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `document_type_id` bigint UNSIGNED NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `slug`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_admin`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'a1b2c3d4-e5f6-4a7b-8c9d-0e1f2a3b4c5d', 'Muhammad Mabrur Al Mutaqi', 'mabruralmutaqi@gmail.com', NULL, '$2y$12$gxXV6B8BYf3M1rT5K4RgAO38S2o01EmcQcATxO9u0eTn6cIa5Dp2S', NULL, 1, 1, '2025-06-04 03:15:46', '2025-06-05 03:01:14'),
(19, 'dbc5d73c-4c06-4066-819a-8ff3b29957c4', 'Michael Kawilarang', 'michael@mitramaritim.com', NULL, '$2y$12$R2mwHYOiiNWMqQP6ZZdNq.nC07iYeSDKGyZcg0SC9WBv7RvjOY3TW', NULL, 0, 1, '2025-06-05 03:44:10', '2025-06-05 03:44:10'),
(21, '26bd0553-12e8-4181-8198-8ccdc43b2cd9', 'Triyanto Bayu Widhiatmoko', 'bayu@petrolindo.com', NULL, '$2y$12$FApQQypS75wvtk5z9pCvTOvtWy/AyRcXO8SXkQsbH0sQXV7gLWNsq', NULL, 0, 1, '2025-06-12 19:15:24', '2025-06-12 20:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `vessels`
--

CREATE TABLE `vessels` (
  `id` bigint UNSIGNED NOT NULL,
  `vessel_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vessels`
--

INSERT INTO `vessels` (`id`, `vessel_name`, `image`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 'KN Ular Laut - 401', NULL, 2, '2025-06-13 02:53:14', '2025-06-13 02:53:14');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_types_created_by_foreign` (`created_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fuels`
--
ALTER TABLE `fuels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fuels_created_by_foreign` (`created_by`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `periodes`
--
ALTER TABLE `periodes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `periodes_created_by_foreign` (`created_by`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permissions_slug_unique` (`slug`),
  ADD KEY `role_permissions_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipments_period_id_foreign` (`period_id`),
  ADD KEY `shipments_termin_id_foreign` (`termin_id`),
  ADD KEY `shipments_vessel_id_foreign` (`vessel_id`),
  ADD KEY `shipments_spk_id_foreign` (`spk_id`),
  ADD KEY `shipments_fuel_id_foreign` (`fuel_id`),
  ADD KEY `shipments_created_by_foreign` (`created_by`);

--
-- Indexes for table `spks`
--
ALTER TABLE `spks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spks_period_id_foreign` (`period_id`),
  ADD KEY `spks_created_by_foreign` (`created_by`);

--
-- Indexes for table `termins`
--
ALTER TABLE `termins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `termins_period_id_foreign` (`period_id`),
  ADD KEY `termins_created_by_foreign` (`created_by`);

--
-- Indexes for table `upload_shipment_documents`
--
ALTER TABLE `upload_shipment_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `upload_shipment_documents_shipment_id_foreign` (`shipment_id`),
  ADD KEY `upload_shipment_documents_document_type_id_foreign` (`document_type_id`),
  ADD KEY `upload_shipment_documents_created_by_foreign` (`created_by`),
  ADD KEY `upload_shipment_documents_period_id_foreign` (`period_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `vessels`
--
ALTER TABLE `vessels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vessels_created_by_foreign` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fuels`
--
ALTER TABLE `fuels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `periodes`
--
ALTER TABLE `periodes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `spks`
--
ALTER TABLE `spks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `termins`
--
ALTER TABLE `termins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `upload_shipment_documents`
--
ALTER TABLE `upload_shipment_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `vessels`
--
ALTER TABLE `vessels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document_types`
--
ALTER TABLE `document_types`
  ADD CONSTRAINT `document_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fuels`
--
ALTER TABLE `fuels`
  ADD CONSTRAINT `fuels_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `periodes`
--
ALTER TABLE `periodes`
  ADD CONSTRAINT `periodes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_fuel_id_foreign` FOREIGN KEY (`fuel_id`) REFERENCES `fuels` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_spk_id_foreign` FOREIGN KEY (`spk_id`) REFERENCES `spks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_termin_id_foreign` FOREIGN KEY (`termin_id`) REFERENCES `termins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_vessel_id_foreign` FOREIGN KEY (`vessel_id`) REFERENCES `vessels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spks`
--
ALTER TABLE `spks`
  ADD CONSTRAINT `spks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spks_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `termins`
--
ALTER TABLE `termins`
  ADD CONSTRAINT `termins_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `termins_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `upload_shipment_documents`
--
ALTER TABLE `upload_shipment_documents`
  ADD CONSTRAINT `upload_shipment_documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `upload_shipment_documents_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `upload_shipment_documents_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `upload_shipment_documents_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vessels`
--
ALTER TABLE `vessels`
  ADD CONSTRAINT `vessels_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
