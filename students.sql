-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2025 at 01:43 PM
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
-- Database: `students`
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
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `station_id` bigint(20) UNSIGNED NOT NULL,
  `person_id` bigint(20) UNSIGNED NOT NULL,
  `photographer_id` bigint(20) UNSIGNED NOT NULL,
  `reference_number` varchar(255) NOT NULL,
  `case_type` varchar(255) NOT NULL,
  `circumstances` text NOT NULL,
  `cause_of_death` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`id`, `station_id`, `person_id`, `photographer_id`, `reference_number`, `case_type`, `circumstances`, `cause_of_death`, `created_at`, `updated_at`) VALUES
(1, 112, 1, 1, '12/05/2024', 'Sudden Death', 'A lady was found hanging in a tree', 'Stab wounds', '2025-07-10 16:43:15', '2025-07-16 10:38:35');

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE `details` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `age` int(3) NOT NULL,
  `gender` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `details`
--

INSERT INTO `details` (`id`, `name`, `email`, `age`, `gender`) VALUES
(1, 'Farai Rudzi', 'farairudzi@gmail.com', 34, 'Male');

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
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `case_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `case_id`, `type`, `path`, `description`, `created_at`, `updated_at`) VALUES
(2, 1, 'image', 'media/hOLUhKWrW0zniomR8LCzAGw5Ir7CM7pHt9uGMrHl.jpg', 'Lying at the scene', '2025-07-16 10:46:10', '2025-07-16 10:46:10');

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
(4, '2025_07_08_131550_create_students_table', 2),
(5, '2025_07_09_151217_add_role_to_users_table', 3),
(6, '2025_07_09_151417_remove_role_from_users_table', 3),
(7, '2025_07_09_152350_create_photographers_table', 3),
(8, '2025_07_09_171928_drop_students_table', 4),
(10, '2025_07_09_172620_create_stations_table', 5),
(11, '2025_07_09_182646_create_people_table', 6),
(12, '2025_07_09_182714_create_case_models_table', 6),
(13, '2025_07_09_182738_create_media_table', 6),
(14, '2025_07_16_134532_add_phone_number_to_photographers_table', 7);

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
-- Table structure for table `people`
--

CREATE TABLE `people` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`id`, `name`, `surname`, `id_number`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Lucia', 'Mukabeta', 'U/K', 'U/K', '2025-07-10 16:43:15', '2025-07-10 16:43:15');

-- --------------------------------------------------------

--
-- Table structure for table `photographers`
--

CREATE TABLE `photographers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `force_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `photographers`
--

INSERT INTO `photographers` (`id`, `force_number`, `first_name`, `surname`, `username`, `email`, `password`, `created_at`, `updated_at`, `phone_number`) VALUES
(1, '071884J', 'Farai', 'Rudzi', 'Rudzi', 'farairudzi01@gmail.com', '$2y$12$pXcfe6j1MkirRIJTgagxAOA2PfCO5MspB495kIOxDvtm0fxYaHlOa', '2025-07-09 13:48:09', '2025-07-16 08:41:33', NULL);

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ZBxEZEmERqRWSh7jqWHrYBsDsDdLPj6B0qxhVzoH', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYlBaS1BnbTdvSVlTVDBNUkNKU01tVkt2SHk4Y3RTRGhkWXJUMTJpUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODA4MS9jYXNlcy9jcmVhdGUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1753252715);

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(6, ' ZRP Chiredzi', NULL, NULL),
(7, ' ZRP Triangle', NULL, NULL),
(8, 'ZRP Banket', NULL, NULL),
(9, 'ZRP Battlefields', NULL, NULL),
(10, 'ZRP Beatrice', NULL, NULL),
(11, 'ZRP Beitbridge Rural', NULL, NULL),
(12, 'ZRP Beitbridge Urban', NULL, NULL),
(13, 'ZRP Bhasera', NULL, NULL),
(14, 'ZRP Bikita', NULL, NULL),
(15, 'ZRP Chakari', NULL, NULL),
(16, 'ZRP Chegutu', NULL, NULL),
(17, 'ZRP Chemagamba', NULL, NULL),
(18, 'ZRP Chikato', NULL, NULL),
(19, 'ZRP Chinamhora', NULL, NULL),
(20, 'ZRP Chingondo', NULL, NULL),
(21, 'ZRP Chinhoyi Central', NULL, NULL),
(22, 'ZRP Chinhoyi Rural', NULL, NULL),
(23, 'ZRP Chirundu', NULL, NULL),
(24, 'ZRP Chivhu', NULL, NULL),
(25, 'ZRP Darwendale', NULL, NULL),
(26, 'ZRP Dema', NULL, NULL),
(27, 'ZRP Dombotombo', NULL, NULL),
(28, 'ZRP Eiffel Flats', NULL, NULL),
(29, 'ZRP Esigodini ', NULL, NULL),
(30, 'ZRP Figtree ', NULL, NULL),
(31, 'ZRP Filabusi', NULL, NULL),
(32, 'ZRP Fort Rixon', NULL, NULL),
(33, 'ZRP Furtherstone', NULL, NULL),
(34, 'ZRP Goromonzi', NULL, NULL),
(35, 'ZRP Guyu ', NULL, NULL),
(36, 'ZRP Gwanda Rural', NULL, NULL),
(37, 'ZRP Gwanda Urban ', NULL, NULL),
(38, 'ZRP Hwedza', NULL, NULL),
(39, 'ZRP Juru', NULL, NULL),
(40, 'ZRP Kadoma Central', NULL, NULL),
(41, 'ZRP Kadoma Rural', NULL, NULL),
(42, 'ZRP Kariba', NULL, NULL),
(43, 'ZRP Karoi Rural', NULL, NULL),
(44, 'ZRP Karoi Urban ', NULL, NULL),
(45, 'ZRP Kenzamba', NULL, NULL),
(46, 'ZRP Kezi ', NULL, NULL),
(47, 'ZRP Kutama ', NULL, NULL),
(48, 'ZRP Macheke', NULL, NULL),
(49, 'ZRP Madlambuzi', NULL, NULL),
(50, 'ZRP Magunje', NULL, NULL),
(51, 'ZRP Mahusekwa', NULL, NULL),
(52, 'ZRP Makosa', NULL, NULL),
(53, 'ZRP Makuti', NULL, NULL),
(54, 'ZRP Mamina', NULL, NULL),
(55, 'ZRP Mangwe', NULL, NULL),
(56, 'ZRP Marondera Central', NULL, NULL),
(57, 'ZRP Marondera Rural ', NULL, NULL),
(58, 'ZRP Masasa', NULL, NULL),
(59, 'ZRP Masvingo Central ', NULL, NULL),
(60, 'ZRP Masvingo Rural ', NULL, NULL),
(61, 'ZRP Matobo', NULL, NULL),
(62, 'ZRP Mayobodo', NULL, NULL),
(63, 'ZRP Mhangura', NULL, NULL),
(64, 'ZRP Mkwasine', NULL, NULL),
(65, 'ZRP Mphoengs', NULL, NULL),
(66, 'ZRP Mubaira', NULL, NULL),
(67, 'ZRP Muchakata ', NULL, NULL),
(68, 'ZRP Murehwa ', NULL, NULL),
(69, 'ZRP Murereka', NULL, NULL),
(70, 'ZRP Mutawatawa', NULL, NULL),
(71, 'ZRP Mutoko', NULL, NULL),
(72, 'ZRP Mutorashanga', NULL, NULL),
(73, 'ZRP Ndali', NULL, NULL),
(74, 'ZRP Norton Rural', NULL, NULL),
(75, 'ZRP Norton Urban', NULL, NULL),
(76, 'ZRP Nyabira', NULL, NULL),
(77, 'ZRP Nyamapanda', NULL, NULL),
(78, 'ZRP Plumtree', NULL, NULL),
(79, 'ZRP Renco ', NULL, NULL),
(80, 'ZRP Rimuka', NULL, NULL),
(81, 'ZRP Rujeko ', NULL, NULL),
(82, 'ZRP Sadza', NULL, NULL),
(83, 'ZRP Sanyati', NULL, NULL),
(84, 'ZRP Saruwe', NULL, NULL),
(85, 'ZRP Siakobvu', NULL, NULL),
(86, 'ZRP Sun-Yet -Sun ', NULL, NULL),
(87, 'ZRP Tengwe', NULL, NULL),
(88, 'ZRP Tuli ', NULL, NULL),
(89, 'ZRP Turf', NULL, NULL),
(90, 'ZRP West Nicholson ', NULL, NULL),
(91, 'ZRP Zezani', NULL, NULL),
(92, 'ZRP Amaveni', NULL, NULL),
(93, 'ZRP Avondale', NULL, NULL),
(94, 'ZRP Avondale Traffic', NULL, NULL),
(95, 'ZRP Bindura Central Police', NULL, NULL),
(96, 'ZRP Bindura Rural', NULL, NULL),
(97, 'ZRP Binga ', NULL, NULL),
(98, 'ZRP Borrowdale ', NULL, NULL),
(99, 'ZRP Braeside', NULL, NULL),
(100, 'ZRP Buchwa ', NULL, NULL),
(101, 'ZRP Budiriro', NULL, NULL),
(102, 'ZRP Buhera', NULL, NULL),
(103, 'ZRP Bulawayo License Inspectorate', NULL, NULL),
(104, 'ZRP Cashel', NULL, NULL),
(105, 'ZRP Centenary', NULL, NULL),
(106, 'ZRP Charandura', NULL, NULL),
(107, 'ZRP Chimanimani', NULL, NULL),
(108, 'ZRP Chipinge Rural', NULL, NULL),
(109, 'ZRP Chipinge Urban', NULL, NULL),
(110, 'ZRP Chisumbanje', NULL, NULL),
(111, 'ZRP Chitungwiza ', NULL, NULL),
(112, 'ZRP Chiwaridzo', NULL, NULL),
(113, 'ZRP Chombira', NULL, NULL),
(114, 'ZRP Concession ', NULL, NULL),
(115, 'ZRP Cowdry Park', NULL, NULL),
(116, 'ZRP Dangamvura', NULL, NULL),
(117, 'ZRP Dete ', NULL, NULL),
(118, 'ZRP Donnington', NULL, NULL),
(119, 'ZRP Dorowa', NULL, NULL),
(120, 'ZRP Dotito', NULL, NULL),
(121, 'ZRP Dzivarasekwa', NULL, NULL),
(122, 'ZRP Entumbane', NULL, NULL),
(123, 'ZRP Epworth', NULL, NULL),
(124, 'ZRP Five Avenue ', NULL, NULL),
(125, 'ZRP Glen Norah ', NULL, NULL),
(126, 'ZRP Glendale ', NULL, NULL),
(127, 'ZRP Glenview ', NULL, NULL),
(128, 'ZRP Gokwe ', NULL, NULL),
(129, 'ZRP Guruve ', NULL, NULL),
(130, 'ZRP Gwelutshena', NULL, NULL),
(131, 'ZRP Gweru Central ', NULL, NULL),
(132, 'ZRP Gweru Rural', NULL, NULL),
(133, 'ZRP Harare Kopje', NULL, NULL),
(134, 'ZRP Hatfield', NULL, NULL),
(135, 'ZRP Headlands', NULL, NULL),
(136, 'ZRP Highlands', NULL, NULL),
(137, 'ZRP Chikanga', NULL, '2025-07-10 16:35:15'),
(138, 'ZRP Hillside', NULL, NULL),
(139, 'ZRP Hwange Central ', NULL, NULL),
(140, 'ZRP Insuza', NULL, NULL),
(141, 'ZRP Inyathi', NULL, NULL),
(143, 'ZRP Kamativi ', NULL, NULL),
(144, 'ZRP Kanyemba', NULL, NULL),
(145, 'ZRP Kazungula ', NULL, NULL),
(146, 'ZRP Khumalo', NULL, NULL),
(147, 'ZRP Kuwadzana ', NULL, NULL),
(148, 'ZRP Kwekwe Central', NULL, NULL),
(149, 'ZRP Kwekwe Rural', NULL, NULL),
(150, 'ZRP Lalapanzi', NULL, NULL),
(151, 'ZRP Lupane', NULL, NULL),
(152, 'ZRP Lusulu ', NULL, NULL),
(153, 'ZRP Luveve', NULL, NULL),
(154, 'ZRP Mabelreign', NULL, NULL),
(155, 'ZRP Maboleni', NULL, NULL),
(156, 'ZRP Mabvuku', NULL, NULL),
(157, 'ZRP Machipisa', NULL, NULL),
(158, 'ZRP Madziwa', NULL, NULL),
(159, 'ZRP Magwegwe', NULL, NULL),
(160, 'ZRP Malborough', NULL, NULL),
(161, 'ZRP Manoti', NULL, NULL),
(162, 'ZRP Marange', NULL, NULL),
(163, 'ZRP Marimba', NULL, NULL),
(164, 'ZRP Mataga', NULL, NULL),
(165, 'ZRP Matapi', NULL, NULL),
(166, 'ZRP Mayo', NULL, NULL),
(167, 'ZRP Mazowe ', NULL, NULL),
(168, 'ZRP Mbare ', NULL, NULL),
(169, 'ZRP Mbembesi', NULL, NULL),
(170, 'ZRP Mberengwa ', NULL, NULL),
(171, 'ZRP Mbizo', NULL, NULL),
(172, 'ZRP Middle Sabi', NULL, NULL),
(173, 'ZRP Milton Park', NULL, NULL),
(174, 'ZRP Mkoba', NULL, NULL),
(175, 'ZRP Momomutapa', NULL, NULL),
(176, 'ZRP Mt Darwin ', NULL, NULL),
(177, 'ZRP Mukumbura', NULL, NULL),
(178, 'ZRP Murambinda', NULL, NULL),
(179, 'ZRP Mushumbi Pools', NULL, NULL),
(180, 'ZRP Mutare Central', NULL, NULL),
(181, 'ZRP Mutare Rural', NULL, NULL),
(182, 'ZRP Mutasa', NULL, NULL),
(183, 'ZRP Muzarabani', NULL, NULL),
(184, 'ZRP Muzokomba', NULL, NULL),
(185, 'ZRP Mvuma', NULL, NULL),
(186, 'ZRP Mvurwi', NULL, NULL),
(187, 'ZRP Mzilikazi ', NULL, NULL),
(188, 'ZRP Nehanda ', NULL, NULL),
(189, 'ZRP Nembudziya ', NULL, NULL),
(190, 'ZRP Njube', NULL, NULL),
(191, 'ZRP Nkayi', NULL, NULL),
(192, 'ZRP Nkulumane', NULL, NULL),
(193, 'ZRP Nyamandlovu', NULL, NULL),
(194, 'ZRP Nyamaropa', NULL, NULL),
(195, 'ZRP Nyanga', NULL, NULL),
(196, 'ZRP Nyanyadzi', NULL, NULL),
(197, 'ZRP Nyazura', NULL, NULL),
(198, 'ZRP Odzi', NULL, NULL),
(199, 'ZRP Penhalonga', NULL, NULL),
(200, 'ZRP Pumula', NULL, NULL),
(201, 'ZRP Queens Park', NULL, NULL),
(202, 'ZRP Redcliff ', NULL, NULL),
(203, 'ZRP Rhodesvielle', NULL, NULL),
(204, 'ZRP Robert Gabriel Mugabe Airport ', NULL, NULL),
(205, 'ZRP Ruda', NULL, NULL),
(206, 'ZRP Rusape Central', NULL, NULL),
(207, 'ZRP Rusape Rural', NULL, NULL),
(208, 'ZRP Rusape Traffic', NULL, NULL),
(209, 'ZRP Rushinga', NULL, NULL),
(210, 'ZRP Ruwa', NULL, NULL),
(211, 'ZRP Ruwangwe', NULL, NULL),
(212, 'ZRP Sakubva', NULL, NULL),
(213, 'ZRP Senga', NULL, NULL),
(214, 'ZRP Shamva', NULL, NULL),
(215, 'ZRP Shurugwi', NULL, NULL),
(216, 'ZRP Siabuwa ', NULL, NULL),
(217, 'ZRP Silobela', NULL, NULL),
(218, 'ZRP Sipepa', NULL, NULL),
(219, 'ZRP Southerton ', NULL, NULL),
(220, 'ZRP Southlea Park ', NULL, NULL),
(221, 'ZRP St Marys', NULL, NULL),
(222, 'ZRP Stodart', NULL, NULL),
(223, 'ZRP Tongogara ', NULL, NULL),
(224, 'ZRP Tshabalala', NULL, NULL),
(225, 'ZRP Tsholotsho', NULL, NULL),
(226, 'ZRP Victoria Falls Airport', NULL, NULL),
(227, 'ZRP Warren Park', NULL, NULL),
(228, 'ZRP Waterfalls ', NULL, NULL),
(229, 'ZRP West Commonage ', NULL, NULL),
(230, 'ZRP Zengeza', NULL, NULL),
(231, 'ZRP Zhombe', NULL, NULL),
(232, 'ZRP Zvishavane ', NULL, NULL),
(233, 'ZRP Chartsworth', NULL, NULL),
(234, 'ZRP Chikombedzi', NULL, NULL),
(235, 'ZRP Chivi', NULL, NULL),
(236, 'ZRP Gutu', NULL, NULL),
(237, 'ZRP Jambezi', NULL, '2025-07-10 16:37:46'),
(238, 'ZRP Joshua Nkomo Airport', NULL, '2025-07-10 16:37:25'),
(239, 'ZRP Jotsholo', NULL, '2025-07-10 16:36:58'),
(240, 'ZRP Mashava', NULL, NULL),
(241, 'ZRP Mashoko', NULL, NULL),
(242, 'ZRP Mwenezi', NULL, NULL),
(243, 'ZRP Ngundu', NULL, NULL),
(244, 'ZRP Saucerstown', NULL, NULL),
(245, 'ZRP Victoria  Falls', NULL, NULL),
(246, 'ZRP Zaka', NULL, NULL),
(247, 'ZRP Zvimba', NULL, NULL),
(248, 'Inyati', NULL, NULL);

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
(1, 'Farai Rudzi', 'farairudzi01@gmail.com', NULL, '$2y$12$ct75CBcQJyG54yt7b8rJSuq4EGwIa2Bj8jQstQj4VApxINaT7WYji', 'CW28ZAeqyOY5DStWvqsYgyPN2eN3y3gQEIDnRYywmm62R3rvfF3vNfrtwhww', '2025-07-09 11:04:45', '2025-07-09 11:53:08');

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
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cases_reference_number_unique` (`reference_number`),
  ADD KEY `cases_station_id_foreign` (`station_id`),
  ADD KEY `cases_person_id_foreign` (`person_id`),
  ADD KEY `cases_photographer_id_foreign` (`photographer_id`);

--
-- Indexes for table `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_case_id_foreign` (`case_id`);

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
-- Indexes for table `people`
--
ALTER TABLE `people`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photographers`
--
ALTER TABLE `photographers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `photographers_force_number_unique` (`force_number`),
  ADD UNIQUE KEY `photographers_username_unique` (`username`),
  ADD UNIQUE KEY `photographers_email_unique` (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stations_name_unique` (`name`);

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
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `details`
--
ALTER TABLE `details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `people`
--
ALTER TABLE `people`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `photographers`
--
ALTER TABLE `photographers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_person_id_foreign` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_photographer_id_foreign` FOREIGN KEY (`photographer_id`) REFERENCES `photographers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_station_id_foreign` FOREIGN KEY (`station_id`) REFERENCES `stations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_case_id_foreign` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
