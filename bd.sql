-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 23 fév. 2025 à 13:14
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offers`
--

INSERT INTO `offers` (`id`, `company_id`, `title`, `description`, `sector`, `location`, `start_date`, `end_date`, `deadline`, `compensation`, `created_at`) VALUES
(8, 17, 'Stage en Marketing Digital', 'Participez à nos campagnes de marketing digital et apprenez les meilleures pratiques.', 'Marketing', 'Oran', '2023-12-01', '2024-03-01', '2025-03-22', 20000, '2025-02-21 23:04:12'),
(9, 17, 'Ingénieur Logiciel', 'Rejoignez notre équipe en tant qu\'ingénieur logiciel et travaillez sur des projets innovants.', 'Informatique', 'Constantine', '2024-01-15', '2024-04-15', '2025-03-22', 60000, '2025-02-21 23:04:12'),
(10, 17, 'Designer Graphique', 'Nous cherchons un designer graphique talentueux pour créer des visuels percutants.', 'Design', 'Alger', '2024-02-01', '2024-05-01', '2025-03-22', 40000, '2025-02-21 23:04:12'),
(11, 17, 'Analyste de Données', 'Participez à l\'analyse de données pour aider à la prise de décision stratégique.', 'Data Science', 'Oran', '2024-03-01', '2024-06-01', '2025-03-22', 55000, '2025-02-21 23:04:12'),
(14, 17, 'Chef de Projet', 'Gérez des projets passionnants et travaillez avec des équipes multidisciplinaires.', 'Gestion de Projet', 'Oran', '2024-06-01', '2024-09-01', '2025-03-22', 70000, '2025-02-21 23:04:12'),
(15, 17, 'Stage en Ressources Humaines', 'Découvrez les métiers des ressources humaines et participez au recrutement.', 'Ressources Humaines', 'Constantine', '2024-07-01', '2024-10-01', '2025-03-22', 30000, '2025-02-21 23:04:12'),
(16, 17, 'Développeur Backend', 'Contribuez au développement backend de nos applications web.', 'Informatique', 'Alger', '2024-08-01', '2024-11-01', '2025-03-22', 45000, '2025-02-21 23:04:12'),
(17, 17, 'Stage en Finance', 'Participez à la gestion financière de l\'entreprise et apprenez les bases de la comptabilité.', 'Finance', 'Oran', '2024-09-01', '2024-12-01', '2025-03-22', 35000, '2025-02-21 23:04:12'),
(18, 17, 'Ingénieur DevOps', 'Rejoignez notre équipe DevOps pour améliorer nos processus de déploiement.', 'Informatique', 'Constantine', '2024-10-01', '2025-01-01', '2025-03-22', 65000, '2025-02-21 23:04:12'),
(19, 17, 'Stage en Design UX/UI', 'Apprenez à concevoir des interfaces utilisateur intuitives et esthétiques.', 'Design', 'Alger', '2024-11-01', '2025-02-01', '2025-03-22', 40000, '2025-02-21 23:04:12'),
(20, 17, 'Analyste Cybersécurité', 'Participez à la sécurisation de nos systèmes et applications.', 'Cybersécurité', 'Oran', '2024-12-01', '2025-03-01', '2025-03-22', 60000, '2025-02-21 23:04:12'),
(21, 17, 'Stage en Journalisme', 'Participez à la rédaction d\'articles et à la couverture médiatique.', 'Journalisme', 'Constantine', '2025-01-01', '2025-04-01', '2025-03-22', 30000, '2025-02-21 23:04:12'),
(22, 17, 'Développeur Frontend', 'Rejoignez notre équipe pour créer des interfaces utilisateur modernes.', 'Informatique', 'Alger', '2025-02-01', '2025-05-01', '2025-03-22', 50000, '2025-02-21 23:04:12'),
(23, 17, 'Stage en Gestion de Projet', 'Apprenez à gérer des projets de A à Z avec notre équipe expérimentée.', 'Gestion de Projet', 'Oran', '2025-03-01', '2025-06-01', '2025-03-22', 40000, '2025-02-21 23:04:12'),
(47, 17, 'Développeur Full Stack', 'test', 'informatique', 'bourdj bou Arreridj', '2025-02-23', '2025-02-28', '2025-02-28', 0, '2025-02-23 12:04:28');

-- --------------------------------------------------------

--
-- Structure de la table `offer_skills`
--

CREATE TABLE `offer_skills` (
  `offer_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offer_skills`
--

INSERT INTO `offer_skills` (`offer_id`, `skill_id`) VALUES
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(8, 5),
(8, 17),
(9, 6),
(9, 7),
(9, 8),
(10, 9),
(10, 10),
(11, 11),
(11, 12),
(14, 17),
(14, 18),
(15, 19),
(15, 20),
(16, 21),
(16, 22),
(17, 23),
(17, 24),
(18, 25),
(18, 26),
(19, 1),
(19, 2),
(19, 3),
(20, 4),
(20, 5),
(21, 6),
(21, 7),
(21, 8),
(22, 9),
(22, 10),
(23, 11),
(23, 12),
(47, 15),
(47, 17),
(47, 22),
(47, 23);

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
-- Structure de la table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `skills`
--

INSERT INTO `skills` (`id`, `name`) VALUES
(25, 'Agile'),
(15, 'Angular'),
(17, 'API REST'),
(22, 'AWS'),
(23, 'Azure'),
(4, 'CSS'),
(28, 'Cybersécurité'),
(30, 'Data Science'),
(24, 'DevOps'),
(11, 'Docker'),
(10, 'Git'),
(18, 'GraphQL'),
(3, 'HTML'),
(9, 'Java'),
(2, 'JavaScript'),
(13, 'Laravel'),
(21, 'Linux'),
(29, 'Machine Learning'),
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `full_name`, `phone`, `address`, `created_at`) VALUES
(17, 'betkaoui34', '$2y$10$ENsz5sPoVFgr66DUtWhx9O6GRN7h/W6WykJiqmRRQyVnKIkiNfJ0C', 'mohammed.betkaoui@univ-bba.dz', 'company', 'mohamed.betkaoui', '0545798034', 'bourdj bou arriredj', '2025-02-20 21:13:56'),
(28, 'test', '$2y$10$1FiasyWzagnjUaWnnVEGzOoBQP3MjtwKtDWnDwGg6McHaK3XwEHe6', 'fezzaniMamin@univ-bba.dz', 'student', 'mohamed.betkaoui', '0545798034', 'bourdj bou arriredj', '2025-02-22 19:44:31'),
(30, 'mohammed', '$2y$10$blfLFkuJTm0004Bkntouk.Vi0U02nPjcCX9ObnVH1CKZL5Lv.Gj42', 'mrb@gmail.com', 'company', 'mrB', '0545798034', 'bourdj bou arriredj', '2025-02-23 10:44:41'),
(32, 'test02', '$2y$10$6MXPADtNn5XgmcAPT9AZT.PtYo64.ntxCRy47rr4/Ten6I4qKC45K', 'btk@gmail.com', 'student', 'mohamed.betkaoui', '0545798034', 'bourdj bou arriredj', '2025-02-23 12:01:25'),
(33, 'aaaaa', '$2y$10$LyC6PU1e5ncTZ4tAcYqoz.0hmzHpMjXw/Ejnc.SaNw/dZaEY71Apa', 'betkaouiaaaa@gmail.com', 'admin', 'mohamed.betkaoui', '0545798034', 'bourdj bou arriredj', '2025-02-23 12:08:20');

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
(28, 1),
(28, 3),
(28, 4),
(28, 5),
(28, 10),
(28, 13),
(28, 17),
(28, 19),
(32, 15),
(32, 17),
(32, 30);

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
-- Index pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`user_id`,`offer_id`),
  ADD KEY `offer_id` (`offer_id`);

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
  ADD KEY `company_id` (`company_id`);

--
-- Index pour la table `offer_skills`
--
ALTER TABLE `offer_skills`
  ADD PRIMARY KEY (`offer_id`,`skill_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Index pour la table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
  ADD CONSTRAINT `offers_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `offer_skills`
--
ALTER TABLE `offer_skills`
  ADD CONSTRAINT `fk_offer_skills_offer` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `offer_skills_ibfk_1` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`),
  ADD CONSTRAINT `offer_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`);

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
