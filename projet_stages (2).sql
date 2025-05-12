-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 12 mai 2025 à 22:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet_stages`
--

-- --------------------------------------------------------

--
-- Structure de la table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `cv_path` varchar(255) NOT NULL,
  `cover_letter` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `applications`
--

INSERT INTO `applications` (`id`, `student_id`, `offer_id`, `cv_path`, `cover_letter`, `status`, `applied_at`) VALUES
(1, 34, 141, '/uploads/cvs/ai.pdf', 'jbmijmihj', 'pending', '2025-02-27 00:22:08'),
(2, 39, 124, '/uploads/cvs/projet.pdf', 'test', 'pending', '2025-04-16 12:43:16'),
(3, 39, 163, '/uploads/cvs/M1TIC-1.pdf', 'test', 'pending', '2025-04-19 16:05:59'),
(4, 39, 164, '/uploads/cvs/rapport_medical_2025-05-07.pdf', 'zzzzzzzz', 'pending', '2025-05-08 16:28:19');

-- --------------------------------------------------------

--
-- Structure de la table `branch`
--

CREATE TABLE `branch` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `branch`
--

INSERT INTO `branch` (`id`, `name`) VALUES
(15, 'Angula'),
(17, 'API REST'),
(22, 'AWS'),
(24, 'DevOpss'),
(11, 'Docker'),
(10, 'Git'),
(18, 'GraphQL'),
(9, 'Java'),
(2, 'JavaScript'),
(13, 'Laravel'),
(21, 'Linux'),
(29, 'Machine Learning'),
(69, 'med'),
(19, 'MongoDB'),
(5, 'MySQL'),
(7, 'Node.js'),
(1, 'PHP'),
(20, 'PostgreSQL'),
(8, 'Python'),
(6, 'React'),
(26, 'Scrum'),
(12, 'Symfony'),
(16, 'TypeScript'),
(27, 'UI/UX Design'),
(14, 'Vue.js');

-- --------------------------------------------------------

--
-- Structure de la table `favorites`
--

CREATE TABLE `favorites` (
  `user_id` int(11) NOT NULL,
  `offer_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(1, 33, 'New offer added: Software Engineer', 0, '2025-02-24 23:34:47'),
(2, 17, 'New offer added: Software Engineer', 0, '2025-02-24 23:34:47'),
(6, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:11:26'),
(7, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:11:26'),
(11, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:20:32'),
(12, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:20:32'),
(16, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:22:29'),
(17, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:22:29'),
(21, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:24:59'),
(22, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:24:59'),
(26, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:29:54'),
(27, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:29:54'),
(31, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:05'),
(35, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:05'),
(36, 34, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:05'),
(37, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:17'),
(41, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:17'),
(42, 34, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:17'),
(43, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:45'),
(47, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:45'),
(48, 34, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:45'),
(49, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:48'),
(53, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:48'),
(54, 34, 'New offer added: Software Engineer', 0, '2025-02-25 18:50:48'),
(55, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:53:30'),
(59, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:53:30'),
(60, 34, 'New offer added: Software Engineer', 0, '2025-02-25 18:53:30'),
(61, 17, 'New offer added: Software Engineer', 0, '2025-02-25 18:53:33'),
(65, 33, 'New offer added: Software Engineer', 0, '2025-02-25 18:53:33'),
(66, 34, 'New offer added: Software Engineer', 0, '2025-02-25 18:53:33'),
(67, 17, 'New offer added: Software Engineer', 0, '2025-02-25 19:00:22'),
(71, 33, 'New offer added: Software Engineer', 0, '2025-02-25 19:00:22'),
(72, 34, 'New offer added: Software Engineer', 0, '2025-02-25 19:00:22'),
(73, 17, 'New offer added: Software Engineer', 0, '2025-02-25 19:00:33'),
(77, 33, 'New offer added: Software Engineer', 0, '2025-02-25 19:00:33'),
(78, 34, 'New offer added: Software Engineer', 0, '2025-02-25 19:00:33'),
(79, 17, 'New offer added: Software Engineer', 0, '2025-02-25 19:01:10'),
(83, 33, 'New offer added: Software Engineer', 0, '2025-02-25 19:01:10'),
(84, 34, 'New offer added: Software Engineer', 0, '2025-02-25 19:01:10'),
(85, 17, 'New offer added: Software Engineer', 0, '2025-02-25 19:01:11'),
(89, 33, 'New offer added: Software Engineer', 0, '2025-02-25 19:01:11'),
(90, 34, 'New offer added: Software Engineer', 0, '2025-02-25 19:01:11'),
(91, 17, 'New offer added: Software Engineer', 0, '2025-02-25 19:02:58'),
(95, 33, 'New offer added: Software Engineer', 0, '2025-02-25 19:02:58'),
(96, 34, 'New offer added: Software Engineer', 0, '2025-02-25 19:02:58'),
(97, 17, 'New offer added: Software Engineer', 0, '2025-02-25 19:06:41'),
(101, 33, 'New offer added: Software Engineer', 0, '2025-02-25 19:06:41'),
(102, 34, 'New offer added: Software Engineer', 0, '2025-02-25 19:06:41'),
(103, 17, 'New offer added: Software Engineer', 0, '2025-02-25 19:06:44'),
(107, 33, 'New offer added: Software Engineer', 0, '2025-02-25 19:06:44'),
(108, 34, 'New offer added: Software Engineer', 0, '2025-02-25 19:06:44'),
(109, 17, 'New offer added: Software Engineer', 0, '2025-02-25 19:08:43'),
(113, 33, 'New offer added: Software Engineer', 0, '2025-02-25 19:08:43'),
(114, 34, 'New offer added: Software Engineer', 0, '2025-02-25 19:08:43'),
(115, 17, 'New offer added: Software Engineer', 0, '2025-02-25 19:08:47'),
(119, 33, 'New offer added: Software Engineer', 0, '2025-02-25 19:08:47'),
(120, 34, 'New offer added: Software Engineer', 0, '2025-02-25 19:08:47'),
(121, 33, 'New offer added: test', 0, '2025-02-25 22:01:14'),
(122, 17, 'New offer added: test', 0, '2025-02-25 22:01:14'),
(123, 34, 'New offer added: test', 0, '2025-02-25 22:01:14'),
(127, 33, 'New offer added: test', 0, '2025-02-25 22:01:57'),
(128, 17, 'New offer added: test', 0, '2025-02-25 22:01:57'),
(129, 34, 'New offer added: test', 0, '2025-02-25 22:01:57'),
(133, 33, 'New offer added: test', 0, '2025-02-25 22:02:04'),
(134, 17, 'New offer added: test', 0, '2025-02-25 22:02:04'),
(135, 34, 'New offer added: test', 0, '2025-02-25 22:02:04'),
(139, 33, 'New offer added: new', 0, '2025-02-25 22:02:40'),
(140, 17, 'New offer added: new', 0, '2025-02-25 22:02:40'),
(141, 34, 'New offer added: new', 0, '2025-02-25 22:02:40'),
(145, 17, 'New offer added: new', 0, '2025-02-25 22:05:44'),
(149, 33, 'New offer added: new', 0, '2025-02-25 22:05:44'),
(150, 34, 'New offer added: new', 0, '2025-02-25 22:05:44'),
(151, 17, 'New offer added: new', 0, '2025-02-25 22:05:49'),
(155, 33, 'New offer added: new', 0, '2025-02-25 22:05:49'),
(156, 34, 'New offer added: new', 0, '2025-02-25 22:05:49'),
(157, 17, 'New offer added: new', 0, '2025-02-25 22:05:59'),
(161, 33, 'New offer added: new', 0, '2025-02-25 22:05:59'),
(162, 34, 'New offer added: new', 0, '2025-02-25 22:05:59'),
(163, 17, 'New offer added: new', 0, '2025-02-25 22:06:23'),
(167, 33, 'New offer added: new', 0, '2025-02-25 22:06:23'),
(168, 34, 'New offer added: new', 0, '2025-02-25 22:06:23'),
(169, 33, 'New offer added: new', 0, '2025-02-25 22:08:11'),
(170, 17, 'New offer added: new', 0, '2025-02-25 22:08:11'),
(171, 34, 'New offer added: new', 0, '2025-02-25 22:08:11'),
(175, 33, 'New offer added: new', 0, '2025-02-25 22:08:17'),
(176, 17, 'New offer added: new', 0, '2025-02-25 22:08:17'),
(177, 34, 'New offer added: new', 0, '2025-02-25 22:08:17'),
(181, 33, 'New offer added: new', 0, '2025-02-25 22:15:40'),
(182, 17, 'New offer added: new', 0, '2025-02-25 22:15:40'),
(183, 34, 'New offer added: new', 0, '2025-02-25 22:15:40'),
(187, 33, 'New offer added: sssssssss', 0, '2025-02-25 22:26:21'),
(188, 17, 'New offer added: sssssssss', 0, '2025-02-25 22:26:21'),
(189, 34, 'New offer added: sssssssss', 0, '2025-02-25 22:26:21'),
(193, 33, 'New offer added: sssssssss', 0, '2025-02-25 22:35:51'),
(194, 17, 'New offer added: sssssssss', 0, '2025-02-25 22:35:51'),
(195, 34, 'New offer added: sssssssss', 0, '2025-02-25 22:35:51'),
(199, 33, 'New offer added: sssssssss', 0, '2025-02-25 22:35:58'),
(200, 17, 'New offer added: sssssssss', 0, '2025-02-25 22:35:58'),
(201, 34, 'New offer added: sssssssss', 0, '2025-02-25 22:35:58'),
(205, 33, 'New offer added: sssssssss', 0, '2025-02-25 22:36:06'),
(206, 17, 'New offer added: sssssssss', 0, '2025-02-25 22:36:06'),
(207, 34, 'New offer added: sssssssss', 0, '2025-02-25 22:36:06'),
(211, 33, 'New offer added: sssssssss', 0, '2025-02-25 22:36:08'),
(212, 17, 'New offer added: sssssssss', 0, '2025-02-25 22:36:08'),
(213, 34, 'New offer added: sssssssss', 0, '2025-02-25 22:36:08'),
(217, 33, 'New offer added: sssssssss', 0, '2025-02-25 22:36:41'),
(218, 17, 'New offer added: sssssssss', 0, '2025-02-25 22:36:41'),
(219, 34, 'New offer added: sssssssss', 0, '2025-02-25 22:36:41'),
(223, 33, 'New offer added: sssssssss', 0, '2025-02-25 22:36:43'),
(224, 17, 'New offer added: sssssssss', 0, '2025-02-25 22:36:43'),
(225, 34, 'New offer added: sssssssss', 0, '2025-02-25 22:36:43'),
(229, 33, 'New offer added: sssssssss', 0, '2025-02-25 22:36:44'),
(230, 17, 'New offer added: sssssssss', 0, '2025-02-25 22:36:44'),
(231, 34, 'New offer added: sssssssss', 0, '2025-02-25 22:36:44'),
(235, 33, 'New offer added: sssssssss', 0, '2025-02-25 22:36:44'),
(236, 17, 'New offer added: sssssssss', 0, '2025-02-25 22:36:44'),
(237, 34, 'New offer added: sssssssss', 0, '2025-02-25 22:36:44'),
(241, 33, 'New offer added: last', 0, '2025-02-25 22:37:11'),
(242, 17, 'New offer added: last', 0, '2025-02-25 22:37:11'),
(243, 34, 'New offer added: last', 0, '2025-02-25 22:37:11'),
(247, 33, 'New offer added: last', 0, '2025-02-25 22:37:30'),
(248, 17, 'New offer added: last', 0, '2025-02-25 22:37:30'),
(249, 34, 'New offer added: last', 0, '2025-02-25 22:37:30'),
(253, 33, 'New offer added: last', 0, '2025-02-25 22:41:48'),
(254, 17, 'New offer added: last', 0, '2025-02-25 22:41:48'),
(255, 34, 'New offer added: last', 0, '2025-02-25 22:41:48'),
(259, 33, 'New offer added: last', 0, '2025-02-25 22:41:52'),
(260, 17, 'New offer added: last', 0, '2025-02-25 22:41:52'),
(261, 34, 'New offer added: last', 0, '2025-02-25 22:41:52'),
(265, 33, 'New offer added: last', 0, '2025-02-25 22:45:49'),
(266, 17, 'New offer added: last', 0, '2025-02-25 22:45:49'),
(267, 34, 'New offer added: last', 0, '2025-02-25 22:45:49'),
(271, 33, 'New offer added: last', 0, '2025-02-25 22:46:11'),
(272, 17, 'New offer added: last', 0, '2025-02-25 22:46:11'),
(273, 34, 'New offer added: last', 0, '2025-02-25 22:46:11'),
(277, 33, 'New offer added: bb', 0, '2025-02-25 23:13:50'),
(278, 17, 'New offer added: bb', 0, '2025-02-25 23:13:50'),
(279, 34, 'New offer added: bb', 0, '2025-02-25 23:13:50'),
(283, 33, 'New offer added: new', 0, '2025-02-25 23:38:30'),
(284, 17, 'New offer added: new', 0, '2025-02-25 23:38:30'),
(285, 34, 'New offer added: new', 0, '2025-02-25 23:38:30'),
(289, 33, 'New offer added: btk', 0, '2025-02-26 08:51:47'),
(290, 17, 'New offer added: btk', 0, '2025-02-26 08:51:47'),
(291, 34, 'New offer added: btk', 0, '2025-02-26 08:51:47'),
(295, 33, 'New offer added: hello', 0, '2025-02-26 12:36:30'),
(296, 17, 'New offer added: hello', 0, '2025-02-26 12:36:30'),
(297, 34, 'New offer added: hello', 0, '2025-02-26 12:36:30'),
(301, 33, 'New offer added: not', 0, '2025-02-26 13:04:43'),
(302, 17, 'New offer added: not', 0, '2025-02-26 13:04:43'),
(303, 34, 'New offer added: not', 0, '2025-02-26 13:04:43'),
(307, 17, 'New offer added: last', 0, '2025-02-26 13:10:04'),
(311, 33, 'New offer added: last', 0, '2025-02-26 13:10:04'),
(312, 34, 'New offer added: last', 0, '2025-02-26 13:10:04'),
(313, 17, 'New offer added: Software Engineer', 0, '2025-02-26 18:33:39'),
(317, 33, 'New offer added: Software Engineer', 0, '2025-02-26 18:33:39'),
(318, 34, 'New offer added: Software Engineer', 0, '2025-02-26 18:33:39'),
(319, 36, 'New offer added: Software Engineer', 0, '2025-02-26 18:33:39'),
(320, 33, 'New offer added: Software Engineer', 0, '2025-02-26 18:35:56'),
(321, 17, 'New offer added: Software Engineer', 0, '2025-02-26 18:35:56'),
(322, 36, 'New offer added: Software Engineer', 0, '2025-02-26 18:35:56'),
(323, 34, 'New offer added: Software Engineer', 0, '2025-02-26 18:35:56'),
(327, 33, 'New offer added: Software Engineer', 0, '2025-02-26 18:36:53'),
(328, 17, 'New offer added: Software Engineer', 0, '2025-02-26 18:36:53'),
(329, 36, 'New offer added: Software Engineer', 0, '2025-02-26 18:36:53'),
(330, 34, 'New offer added: Software Engineer', 0, '2025-02-26 18:36:53'),
(334, 33, 'New offer added: Software Engineer', 0, '2025-02-26 18:39:50'),
(335, 17, 'New offer added: Software Engineer', 0, '2025-02-26 18:39:50'),
(336, 36, 'New offer added: Software Engineer', 0, '2025-02-26 18:39:50'),
(337, 34, 'New offer added: Software Engineer', 0, '2025-02-26 18:39:50'),
(341, 33, 'New offer added: Software Engineer', 0, '2025-02-26 18:40:12'),
(342, 17, 'New offer added: Software Engineer', 0, '2025-02-26 18:40:12'),
(343, 36, 'New offer added: Software Engineer', 0, '2025-02-26 18:40:12'),
(344, 34, 'New offer added: Software Engineer', 0, '2025-02-26 18:40:12'),
(348, 17, 'New offer added: Software Engineer', 0, '2025-02-26 18:49:17'),
(352, 33, 'New offer added: Software Engineer', 0, '2025-02-26 18:49:17'),
(353, 34, 'New offer added: Software Engineer', 0, '2025-02-26 18:49:17'),
(354, 36, 'New offer added: Software Engineer', 0, '2025-02-26 18:49:17'),
(355, 17, 'New offer added: Software Engineer', 0, '2025-02-26 18:50:38'),
(359, 33, 'New offer added: Software Engineer', 0, '2025-02-26 18:50:38'),
(360, 34, 'New offer added: Software Engineer', 0, '2025-02-26 18:50:38'),
(361, 36, 'New offer added: Software Engineer', 0, '2025-02-26 18:50:38'),
(362, 33, 'New offer added: Software Engineer', 0, '2025-02-26 18:52:43'),
(363, 17, 'New offer added: Software Engineer', 0, '2025-02-26 18:52:43'),
(364, 36, 'New offer added: Software Engineer', 0, '2025-02-26 18:52:43'),
(365, 34, 'New offer added: Software Engineer', 0, '2025-02-26 18:52:43'),
(369, 17, 'New offer added: Software Engineer', 0, '2025-02-26 18:59:49'),
(373, 33, 'New offer added: Software Engineer', 0, '2025-02-26 18:59:49'),
(374, 34, 'New offer added: Software Engineer', 0, '2025-02-26 18:59:49'),
(375, 36, 'New offer added: Software Engineer', 0, '2025-02-26 18:59:49'),
(376, 33, 'New offer added: Software Engineer', 0, '2025-02-26 19:01:07'),
(377, 17, 'New offer added: Software Engineer', 0, '2025-02-26 19:01:07'),
(378, 36, 'New offer added: Software Engineer', 0, '2025-02-26 19:01:07'),
(379, 34, 'New offer added: Software Engineer', 0, '2025-02-26 19:01:07'),
(383, 33, 'New offer added: Software Engineer', 0, '2025-02-26 19:02:02'),
(384, 17, 'New offer added: Software Engineer', 0, '2025-02-26 19:02:02'),
(385, 36, 'New offer added: Software Engineer', 0, '2025-02-26 19:02:02'),
(386, 34, 'New offer added: Software Engineer', 0, '2025-02-26 19:02:02'),
(390, 17, 'New offer added: Software Engineer', 0, '2025-02-26 23:15:47'),
(394, 33, 'New offer added: Software Engineer', 0, '2025-02-26 23:15:47'),
(395, 34, 'New offer added: Software Engineer', 0, '2025-02-26 23:15:47'),
(396, 36, 'New offer added: Software Engineer', 0, '2025-02-26 23:15:47'),
(397, 33, 'New offer added: Software Engineer', 0, '2025-02-26 23:53:12'),
(398, 17, 'New offer added: Software Engineer', 0, '2025-02-26 23:53:12'),
(399, 36, 'New offer added: Software Engineer', 0, '2025-02-26 23:53:12'),
(400, 34, 'New offer added: Software Engineer', 0, '2025-02-26 23:53:12'),
(404, 17, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:24'),
(408, 33, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:24'),
(409, 34, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:24'),
(410, 36, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:24'),
(411, 17, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:26'),
(415, 33, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:26'),
(416, 34, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:26'),
(417, 36, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:26'),
(418, 17, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:28'),
(422, 33, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:28'),
(423, 34, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:28'),
(424, 36, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:28'),
(425, 17, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:29'),
(429, 33, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:29'),
(430, 34, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:29'),
(431, 36, 'New offer added: Software Engineer', 0, '2025-02-26 23:54:29'),
(432, 17, 'New offer added: Software Engineer', 0, '2025-02-26 23:55:01'),
(436, 33, 'New offer added: Software Engineer', 0, '2025-02-26 23:55:01'),
(437, 34, 'New offer added: Software Engineer', 0, '2025-02-26 23:55:01'),
(438, 36, 'New offer added: Software Engineer', 0, '2025-02-26 23:55:01'),
(439, 17, 'New offer added: Software Engineer', 0, '2025-02-27 00:04:13'),
(443, 33, 'New offer added: Software Engineer', 0, '2025-02-27 00:04:13'),
(444, 34, 'New offer added: Software Engineer', 0, '2025-02-27 00:04:13'),
(445, 36, 'New offer added: Software Engineer', 0, '2025-02-27 00:04:13'),
(449, 33, 'New offer added: Software Engineer', 0, '2025-02-27 00:05:12'),
(450, 17, 'New offer added: Software Engineer', 0, '2025-02-27 00:05:12'),
(451, 34, 'New offer added: Software Engineer', 0, '2025-02-27 00:05:12'),
(452, 36, 'New offer added: Software Engineer', 0, '2025-02-27 00:05:12'),
(453, 17, 'New offer added: Software Engineer', 0, '2025-02-27 00:07:22'),
(457, 33, 'New offer added: Software Engineer', 0, '2025-02-27 00:07:22'),
(458, 34, 'New offer added: Software Engineer', 0, '2025-02-27 00:07:22'),
(459, 36, 'New offer added: Software Engineer', 0, '2025-02-27 00:07:22'),
(460, 17, 'New offer added: Software Engineer', 0, '2025-02-27 00:07:33'),
(464, 33, 'New offer added: Software Engineer', 0, '2025-02-27 00:07:33'),
(465, 34, 'New offer added: Software Engineer', 0, '2025-02-27 00:07:33'),
(466, 36, 'New offer added: Software Engineer', 0, '2025-02-27 00:07:33'),
(467, 17, 'New offer added: Software Engineer', 0, '2025-02-27 00:09:02'),
(471, 33, 'New offer added: Software Engineer', 0, '2025-02-27 00:09:02'),
(472, 34, 'New offer added: Software Engineer', 0, '2025-02-27 00:09:02'),
(473, 36, 'New offer added: Software Engineer', 0, '2025-02-27 00:09:02'),
(474, 17, 'New offer added: Software Engineer', 0, '2025-02-27 00:10:23'),
(478, 33, 'New offer added: Software Engineer', 0, '2025-02-27 00:10:23'),
(479, 34, 'New offer added: Software Engineer', 0, '2025-02-27 00:10:23'),
(480, 36, 'New offer added: Software Engineer', 0, '2025-02-27 00:10:23'),
(484, 33, 'New offer added: Software Engineer', 0, '2025-02-27 00:17:01'),
(485, 17, 'New offer added: Software Engineer', 0, '2025-02-27 00:17:01'),
(486, 34, 'New offer added: Software Engineer', 0, '2025-02-27 00:17:01'),
(487, 36, 'New offer added: Software Engineer', 0, '2025-02-27 00:17:01'),
(491, 33, 'New offer added: Software Engineer', 0, '2025-02-27 00:17:03'),
(492, 17, 'New offer added: Software Engineer', 0, '2025-02-27 00:17:03'),
(493, 34, 'New offer added: Software Engineer', 0, '2025-02-27 00:17:03'),
(494, 36, 'New offer added: Software Engineer', 0, '2025-02-27 00:17:03'),
(498, 33, 'New offer added: nno', 0, '2025-02-27 12:08:25'),
(499, 17, 'New offer added: nno', 0, '2025-02-27 12:08:25'),
(500, 34, 'New offer added: nno', 0, '2025-02-27 12:08:25'),
(501, 36, 'New offer added: nno', 0, '2025-02-27 12:08:25'),
(505, 33, 'New offer added: Software Engineer', 0, '2025-02-27 16:55:43'),
(506, 17, 'New offer added: Software Engineer', 0, '2025-02-27 16:55:43'),
(507, 34, 'New offer added: Software Engineer', 0, '2025-02-27 16:55:43'),
(508, 36, 'New offer added: Software Engineer', 0, '2025-02-27 16:55:43'),
(512, 33, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:07'),
(513, 17, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:07'),
(514, 34, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:07'),
(515, 36, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:07'),
(519, 33, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:28'),
(520, 17, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:28'),
(521, 34, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:28'),
(522, 36, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:28'),
(526, 33, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:38'),
(527, 17, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:38'),
(528, 34, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:38'),
(529, 36, 'New offer added: Software Engineer', 0, '2025-02-27 18:12:38'),
(533, 33, 'New offer added: Software Engineer', 0, '2025-02-27 18:13:02'),
(534, 17, 'New offer added: Software Engineer', 0, '2025-02-27 18:13:02'),
(535, 34, 'New offer added: Software Engineer', 0, '2025-02-27 18:13:02'),
(536, 36, 'New offer added: Software Engineer', 0, '2025-02-27 18:13:02'),
(540, 33, 'New offer added: Software Engineer', 0, '2025-02-27 18:13:25'),
(541, 17, 'New offer added: Software Engineer', 0, '2025-02-27 18:13:25'),
(542, 34, 'New offer added: Software Engineer', 0, '2025-02-27 18:13:25'),
(543, 36, 'New offer added: Software Engineer', 0, '2025-02-27 18:13:25'),
(565, 34, 'New offer added: Software Engineer', 0, '2025-02-27 18:38:01'),
(566, 36, 'New offer added: Software Engineer', 0, '2025-02-27 18:38:01'),
(567, 34, 'New offer added: Software Engineer', 0, '2025-02-27 19:50:02'),
(568, 36, 'New offer added: Software Engineer', 0, '2025-02-27 19:50:02'),
(569, 34, 'New offer added: Software Engineer', 0, '2025-02-27 19:50:03'),
(570, 36, 'New offer added: Software Engineer', 0, '2025-02-27 19:50:03'),
(572, 39, 'New offer added: test', 0, '2025-04-20 10:40:40');

-- --------------------------------------------------------

--
-- Structure de la table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `sector` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `deadline` date DEFAULT (curdate() + interval 30 day),
  `compensation` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offers`
--

INSERT INTO `offers` (`id`, `company_id`, `title`, `description`, `sector`, `location`, `start_date`, `end_date`, `deadline`, `compensation`, `created_at`, `branch_id`) VALUES
(124, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-05-28', 5000, '2025-02-26 19:01:07', 2),
(126, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-09-28', 5000, '2025-02-26 23:15:47', 2),
(127, 17, 'Software Engineer', 'it', 'it', 'bba', '2025-02-27', '2025-02-28', '2025-10-28', 0, '2025-02-26 23:53:12', 29),
(128, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-09-28', 5000, '2025-02-26 23:54:24', 2),
(129, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-10-28', 5000, '2025-02-26 23:54:26', 2),
(130, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-12-28', 5000, '2025-02-26 23:54:28', 2),
(131, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-12-28', 5000, '2025-02-26 23:54:29', 2),
(132, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-12-28', 5000, '2025-02-26 23:55:01', 2),
(133, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-12-28', 5000, '2025-02-27 00:04:13', 2),
(134, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-12-28', 5000, '2025-02-27 00:05:12', 2),
(136, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-11-28', 5000, '2025-02-27 00:07:33', 9),
(137, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-12-28', 5000, '2025-02-27 00:09:02', 9),
(138, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-02-28', 5000, '2025-02-27 00:10:23', 9),
(141, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-02-28', 5000, '2025-02-27 00:17:01', 9),
(142, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-02-28', 5000, '2025-02-27 00:17:03', 9),
(144, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-02-28', 5000, '2025-02-27 16:55:43', 9),
(163, 17, 'Software Engineer', 'We are looking for a skilled software engineer.', 'IT', 'New York', '2025-03-01', '2025-06-01', '2025-02-28', 5000, '2025-03-07 17:34:48', 19),
(164, 17, 'UI/UX', 'We are looking for a skilled software engineee', 'UI/UX', 'BBA', '2025-03-01', '2025-06-01', '2025-02-28', 5000, '2025-03-07 17:35:14', 15);

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('student','company','admin') NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_branch` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `full_name`, `phone`, `address`, `created_at`, `id_branch`) VALUES
(17, 'betkaoui34', '$2y$10$ENsz5sPoVFgr66DUtWhx9O6GRN7h/W6WykJiqmRRQyVnKIkiNfJ0C', 'mohammed.betkaoui@univ-bba.dz', 'admin', 'mohamed.betkaoui', '0545798037', 'bourdj bou arriredj', '2025-02-20 20:13:56', 5),
(33, 'abbbb', '$2y$10$LyC6PU1e5ncTZ4tAcYqoz.0hmzHpMjXw/Ejnc.SaNw/dZaEY71Apa', 'betkaouiaaaa@gmail.com', 'admin', 'mohamed.betkaoui', '0545798034', 'bourdj bou arriredj', '2025-02-23 12:08:20', NULL),
(34, 'mmainouf', '$2y$10$Pch.kdE9AR1P.1tGdlACtOt673Ptpcw/tpmR.wjo5h6VkS5l6H4SC', 'maminou@gmail.com', 'admin', 'maminou', '249848', 'bba', '2025-02-25 18:33:45', 18),
(36, 'btk', '$2y$10$8Od/VqWbFZbJn3KLNgL9p.7WGf4xlMEJfjhT6y1dDii4dCrIMS8vS', 'btkkkkk@gmail.com', 'admin', 'btknom', '594566', 'bba', '2025-02-26 18:01:00', 18),
(39, 'alilo', '$2y$10$v1Xdn80mWe4V7FezL47aBuIgDPfXZwFt05mMgvrHdRGlJVtfoseDO', 'alilo@gmail.com', 'student', 'Alilo Guichi', '0667895412', 'bourdj bou arriredj', '2025-04-16 12:42:02', 13),
(40, 'test', '$2y$10$Ekt0kQyPVgS2bvgNYR98u.KCr.Cw2pTWS6ZKz36NoKepxIf3aLd/6', 'univ@email.com', 'company', 'bba univ', '0769885648', 'bourdj bou arriredj', '2025-04-17 15:50:33', NULL),
(41, 'mohammed.betkaoui', '$2y$10$OhduQpMwA1X0gkPGJtRXNeqwxccwk1BC7Z1BdVSH13C2VUtucW/j6', 'mohammed.betkaoui@gmail.com', 'company', 'mohamed.betkaoui', '0545798034', 'bourdj bou arriredj', '2025-04-20 10:38:23', NULL),
(43, 'mohammedbba', '$2y$10$TT5ilXrPG4CHhxN9J1uOpulFp2AFOuEN/8IQcT./Btdh3OV6fHTRi', 'test@gmail.com', 'student', 'mohamedbba', '0545798034', 'bourdj bou arriredj', '2025-04-20 11:14:12', 21),
(44, 'alilo@gmail.com', '$2y$10$6klCbN2/zYfZr1xDz3AZu.wJpnYIqiNzs6jfoLD8DIxOlZrzJNcrG', 'gichiali@gmail.com', 'company', 'mohamed.betkaoui', '0545798034', 'bourdj bou arriredj', '2025-04-20 20:47:35', NULL),
(45, 'test111', '$2y$10$2iOOFwMS8ZSR0sCY/XmwQefd/zt0sls0oP99uFuyfdtUhLzY9tzm2', 'test111@gmail.com', 'company', 'test111', '0545798034', 'bourdj bou arriredj', '2025-04-20 20:48:14', NULL),
(47, 'test999', '$2y$10$VjE5e4uTGaXDq1.Iw3oEMOfVPNfyhqeu7umiqmoLiA/pu/gt1HQ9.', 'betkaoui@gmail.com', 'student', 'mohamed.betkaoui', '0545798034', 'bourdj bou arriredj', '2025-04-21 20:20:42', 13),
(48, 'test999000', '$2y$10$qtt7DGnLB/kSVAKj/Pguk.ZaIkIrsewra/GGlC5j2OMhwOZ7iPMeS', 'gichialisss@gmail.com', 'company', 'mohamed.betkaoui', '0545798034', 'bourdj bou arriredj', '2025-04-21 20:24:15', NULL),
(49, 'test0', '$2y$10$kGhNQVk86eKL2x2dd5o8V.qkMBAIrW0x2V5XQ8ig4oSa5wbPGTevO', 'alilo00@gmail.com', 'company', 'yahia Bentaleb', '0545798034', 'bourdj bou arriredj', '2025-04-21 20:35:44', NULL),
(50, 'kamel', '$2y$10$fQFxajpoTlpaDSElQ6I6FeLxct30o/p2A4YGOyWMdmK9bwmo6v.AG', 'kamel@gmail.com', 'student', 'kamel betkaoui', '0545798034', 'bourdj bou arriredj', '2025-04-21 20:54:53', 21),
(51, 'wwww', '$2y$10$AESVVqNXFdMZ4BJJAQ7Pz.ZDWV6B7RMnFxuL0dKQpRGcMFFkJiQNC', 'w@gmail.com', 'student', 'wwww', '0545798034', 'bourdj bou arriredj', '2025-04-23 07:46:21', 21),
(55, 'wail', '$2y$10$8II8C/7hRUo.KL5aS3Qzc.7jMPB2MQPyfbBFL9S27H0SHVtIQbev.', 'wail@gmail.com', 'company', 'test34', '0545798034', 'bourdj bou arriredj', '2025-05-12 20:15:44', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_skills`
--

CREATE TABLE `user_skills` (
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_skills`
--

INSERT INTO `user_skills` (`user_id`, `skill_id`) VALUES
(34, 1),
(34, 2),
(34, 5),
(34, 6),
(34, 7),
(34, 8),
(34, 9),
(34, 10),
(34, 11),
(34, 12),
(34, 13),
(34, 14),
(34, 15),
(34, 16),
(34, 17),
(34, 18),
(34, 19),
(34, 20),
(34, 21),
(34, 22),
(34, 24),
(34, 26),
(34, 27),
(34, 29);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `offer_id` (`offer_id`);

--
-- Index pour la table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`user_id`,`offer_id`),
  ADD KEY `offer_id` (`offer_id`);

--
-- Index pour la table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `fk_branch` (`branch_id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_branch` (`id_branch`);

--
-- Index pour la table `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`user_id`,`skill_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `branch`
--
ALTER TABLE `branch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=573;

--
-- AUTO_INCREMENT pour la table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`);

--
-- Contraintes pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`);

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `offers`
--
ALTER TABLE `offers`
  ADD CONSTRAINT `fk_branch` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`),
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_branch` FOREIGN KEY (`id_branch`) REFERENCES `branch` (`id`);

--
-- Contraintes pour la table `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
