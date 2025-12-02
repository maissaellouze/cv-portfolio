-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 02 déc. 2025 à 17:58
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
-- Base de données : `cv_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `intro` text DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `projects_count` int(11) DEFAULT NULL,
  `technologies_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `about`
--

INSERT INTO `about` (`id`, `intro`, `experience_years`, `projects_count`, `technologies_count`) VALUES
(1, 'Étudiante en deuxième année de Génie Logiciel, passionnée par le développement d\'applications innovantes et performantes.', 4, 8, 12);

-- --------------------------------------------------------

--
-- Structure de la table `associations`
--

CREATE TABLE `associations` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `organization` varchar(100) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `associations`
--

INSERT INTO `associations` (`id`, `title`, `organization`, `start_date`, `end_date`, `description`, `logo_url`) VALUES
(1, 'Secrétaire Générale', 'ARSII - ISSAT Sousse', '2025-10-01', NULL, 'Coordination de la communication interne entre les membres du bureau et les différentes équipes.\nOrganisation administrative des réunions : comptes rendus, suivi des décisions et gestion des documents officiels.\nParticipation à la planification et à la réussite des événements techniques et associatifs.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ5rXwjsAIUZq6jvU6pIl1P0AyhfCZe1xpHnA&s'),
(2, 'Responsable Relations Externes', 'Microsoft Technology Club - ISET Sfax', '2023-04-01', '2024-04-30', 'Représentation du club dans les partenariats et collaborations.\nCommunication externe avec organisations et sponsors.\nCoordination avec intervenants pour événements techniques.', NULL),
(4, 'Vice presidente', 'ARSII - ISSAT Sousse', '2025-12-01', '0000-00-00', 'Assister et remplacer le président \r\nCoordination et supervision des activités \r\nCommunication et représentation ', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ5rXwjsAIUZq6jvU6pIl1P0AyhfCZe1xpHnA&amp;s');

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `type`, `value`) VALUES
(1, 'Email', 'maissaellouze02@gmail.com'),
(2, 'Téléphone', '+21629093786'),
(3, 'Localisation', 'Sfax, Tunisie');

-- --------------------------------------------------------

--
-- Structure de la table `education`
--

CREATE TABLE `education` (
  `id` int(11) NOT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `institution` varchar(100) DEFAULT NULL,
  `start_year` year(4) DEFAULT NULL,
  `end_year` year(4) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `education`
--

INSERT INTO `education` (`id`, `degree`, `institution`, `start_year`, `end_year`, `description`) VALUES
(1, 'Diplôme d\'Ingénieur en Génie Logiciel', 'Institut Supérieur des Sciences Appliquées et de Technologie de Sousse (ISSAT Sousse)', '2024', '2025', 'Formation approfondie en génie logiciel, architecture des systèmes et méthodologies de développement avancées.'),
(2, 'Licence en Technologie de l\'Information (TI)', 'Institut Supérieur des Études Technologiques de Sfax (ISET Sfax)', '2021', '2024', 'Formation complète en développement web et mobile, bases de données et technologies informatiques modernes.');

-- --------------------------------------------------------

--
-- Structure de la table `experience`
--

CREATE TABLE `experience` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `tech_stack` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `experience`
--

INSERT INTO `experience` (`id`, `title`, `company`, `location`, `start_date`, `end_date`, `description`, `tech_stack`) VALUES
(1, 'Stage Introductif', 'Intellitech', 'Sfax', '2025-07-01', '2025-08-31', 'Conception et développement d\'une application de suivi pour tests médicaux à domicile. Gestion des demandes patients avec suivi en temps réel du statut.', 'Spring Boot,Angular,PostgreSQL,Docker'),
(2, 'Stage de Fin d\'Études', 'KMF', 'Sfax', '2024-02-01', '2024-05-31', 'Intégration d\'un dashboard de gestion d\'équipements dans un ERP existant. Suivi et monitoring efficace des actifs de l\'entreprise.', 'Spring Boot,Angular,JasperReports,PostgreSQL'),
(3, 'Stage Optionnel', 'KMF', 'Sfax', '2023-07-01', '2023-09-30', 'Conception et développement d\'un système de gestion bancaire complet. Gestion des comptes, transactions et dossiers clients.', 'Spring Boot,Angular,JasperReports,MySQL'),
(4, 'Stage d\'Amélioration', 'Artibedded', 'Sfax', '2023-01-01', '2023-02-28', 'Application desktop (Java) pour gestion des dossiers enfants et enseignants. Application web (PHP) pour accès en ligne selon droits d\'accès.', 'Java,PHP,MySQL'),
(5, 'Stage Introductif', 'Clinisys', 'Sfax', '2022-01-01', '2022-02-28', 'Expérience pratique au département technique de l\'entreprise. Formatage PC et installation système. Dépannage matériel et support technique.', 'Support IT,Hardware,Windows');

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `features` text DEFAULT NULL,
  `tech_stack` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `start_date`, `end_date`, `features`, `tech_stack`) VALUES
(1, 'Application Web de Gestion Client', 'Module de gestion client intégré dans un système d\'agence commerciale avec opérations CRUD complètes.', '2023-11-01', '2023-12-31', 'Gestion efficace des données clients\nRapports statistiques personnalisables\nTests unitaires avec Mockito', 'Spring Boot,Angular,JasperReports,Mockito'),
(2, 'Application CV Mobile', 'Application mobile présentant un CV personnel de manière interactive et moderne.', '2023-10-01', '2023-11-30', 'Design moderne et interface intuitive\nExport et partage du CV en format PDF\nImpression facilitée', 'Flutter,Dart,PDF'),
(3, 'Site Web ArtyProd', 'Site vitrine pour agence de design présentant services et créations aux visiteurs en ligne.', '2023-04-01', '2023-05-31', 'Présentation des services de design\nUpload et partage de créations\nEnvironnement collaboratif et interactif', 'Django,Python,HTML/CSS'),
(4, 'Application de Messagerie', 'Service de messagerie permettant envoi d\'emails et chat en temps réel entre utilisateurs.', '2022-11-01', '2022-12-31', 'Authentification utilisateur sécurisée\nComposition et gestion d\'emails\nInterface de chat basique en temps réel', 'PHP,MySQL,JavaScript');

-- --------------------------------------------------------

--
-- Structure de la table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `skill_name` varchar(50) DEFAULT NULL,
  `level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `skills`
--

INSERT INTO `skills` (`id`, `category`, `skill_name`, `level`) VALUES
(1, 'Langages de Programmation', 'JavaScript / TypeScript', 90),
(2, 'Langages de Programmation', 'Java', 85),
(3, 'Langages de Programmation', 'Python', 80),
(4, 'Langages de Programmation', 'PHP', 75),
(5, 'Langages de Programmation', 'Dart', 80),
(6, 'Langages de Programmation', 'C', 50),
(7, 'Frameworks Backend & Frontend', 'Spring Boot', 90),
(8, 'Frameworks Backend & Frontend', 'Django', 85),
(9, 'Frameworks Backend & Frontend', 'Node.js', 80),
(10, 'Frameworks Backend & Frontend', 'Angular', 85),
(11, 'Frameworks Backend & Frontend', 'React', 80),
(12, 'Frameworks Backend & Frontend', 'Flutter', 80),
(13, 'Bases de Données', 'PostgreSQL', 80),
(16, 'Bases de Données', 'SQL Server', 70),
(17, 'Bases de Données', 'Oracle', 70),
(18, 'Outils & Technologies', 'Git', 80),
(19, 'Outils & Technologies', 'Docker', 75),
(20, 'Outils & Technologies', 'JasperReports', 70),
(21, 'Outils & Technologies', 'Mockito', 70),
(22, 'Outils & Technologies', 'Windows', 80),
(23, 'Outils & Technologies', 'Linux', 80),
(24, 'Langues', 'Arabe (Natif)', 100),
(25, 'Langues', 'Français (Courant)', 90),
(26, 'Langues', 'Anglais (Courant)', 90),
(27, 'Langues', 'Espagnol (Débutant)', 50),
(28, 'Compétences Comportementales', 'Communication', 90),
(29, 'Compétences Comportementales', 'Travail d\'équipe', 90),
(30, 'Compétences Comportementales', 'Gestion du temps', 85),
(31, 'Compétences Comportementales', 'Résolution de problèmes', 85),
(32, 'Bases de Données', 'MySQL', 90);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `associations`
--
ALTER TABLE `associations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `associations`
--
ALTER TABLE `associations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `education`
--
ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
