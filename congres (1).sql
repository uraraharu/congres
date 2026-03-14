-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 14 mars 2026 à 09:28
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `congres`
--

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

DROP TABLE IF EXISTS `activite`;
CREATE TABLE IF NOT EXISTS `activite` (
  `id_activite` int NOT NULL AUTO_INCREMENT,
  `nom_activite` varchar(50) DEFAULT NULL,
  `description` text,
  `prix_activite` decimal(15,2) DEFAULT NULL,
  `date_heure` datetime DEFAULT NULL,
  PRIMARY KEY (`id_activite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `congressiste`
--

DROP TABLE IF EXISTS `congressiste`;
CREATE TABLE IF NOT EXISTS `congressiste` (
  `id_congressiste` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `acompte` tinyint(1) DEFAULT NULL,
  `password` text,
  `supplement_petit_dejeuner` tinyint(1) DEFAULT NULL,
  `nb_etoile_souhaite` int DEFAULT NULL,
  `id_organisme` int DEFAULT NULL,
  `id_hotel` int DEFAULT NULL,
  PRIMARY KEY (`id_congressiste`),
  KEY `id_organisme` (`id_organisme`),
  KEY `id_hotel` (`id_hotel`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `congressiste`
--

INSERT INTO `congressiste` (`id_congressiste`, `nom`, `prenom`, `adresse`, `email`, `acompte`, `password`, `supplement_petit_dejeuner`, `nb_etoile_souhaite`, `id_organisme`, `id_hotel`) VALUES
(1, 'Marquant', 'Élodie', 'Test 21 test', 'marquant@email.com', NULL, '$2y$10$3TecLc8qa/MHh7og8qVGJ.WHNYFghU2Ed3EbaYEW7aLcj243xcVTO', 0, 3, NULL, NULL),
(2, 'Test', 'Test', 'Test', 'test@email.com', NULL, '$2y$10$3TecLc8qa/MHh7og8qVGJ.WHNYFghU2Ed3EbaYEW7aLcj243xcVTO', 0, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

DROP TABLE IF EXISTS `facture`;
CREATE TABLE IF NOT EXISTS `facture` (
  `id_facture` int NOT NULL AUTO_INCREMENT,
  `date_facture` date DEFAULT NULL,
  `statut_reglement` tinyint(1) DEFAULT NULL,
  `id_organisme` int DEFAULT NULL,
  `id_congressiste` int NOT NULL,
  PRIMARY KEY (`id_facture`),
  UNIQUE KEY `id_congressiste` (`id_congressiste`),
  KEY `id_organisme` (`id_organisme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hotel`
--

DROP TABLE IF EXISTS `hotel`;
CREATE TABLE IF NOT EXISTS `hotel` (
  `id_hotel` int NOT NULL AUTO_INCREMENT,
  `nom_hotel` varchar(50) DEFAULT NULL,
  `adresse_hotel` varchar(50) DEFAULT NULL,
  `prix` decimal(15,2) DEFAULT NULL,
  `prix_supplement_petit_dejeuner` decimal(15,2) DEFAULT NULL,
  `etoile` int DEFAULT NULL,
  `chambre_disponible` int DEFAULT NULL,
  PRIMARY KEY (`id_hotel`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `hotel`
--

INSERT INTO `hotel` (`id_hotel`, `nom_hotel`, `adresse_hotel`, `prix`, `prix_supplement_petit_dejeuner`, `etoile`, `chambre_disponible`) VALUES
(1, 'test', 'test', 10.00, 0.00, 3, 19),
(5, 'testencore', 'diozjqid', 25.00, 0.00, 5, 74);

-- --------------------------------------------------------

--
-- Structure de la table `organisme_payeur`
--

DROP TABLE IF EXISTS `organisme_payeur`;
CREATE TABLE IF NOT EXISTS `organisme_payeur` (
  `id_organisme` int NOT NULL AUTO_INCREMENT,
  `nom_organisme` varchar(50) DEFAULT NULL,
  `adresse_organisme` varchar(50) DEFAULT NULL,
  `email_organisme` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_organisme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participation_activite`
--

DROP TABLE IF EXISTS `participation_activite`;
CREATE TABLE IF NOT EXISTS `participation_activite` (
  `id_congressiste` int NOT NULL,
  `id_activite` int NOT NULL,
  PRIMARY KEY (`id_congressiste`,`id_activite`),
  KEY `id_activite` (`id_activite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participation_session`
--

DROP TABLE IF EXISTS `participation_session`;
CREATE TABLE IF NOT EXISTS `participation_session` (
  `id_congressiste` int NOT NULL,
  `id_session` int NOT NULL,
  PRIMARY KEY (`id_congressiste`,`id_session`),
  KEY `id_session` (`id_session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reglement`
--

DROP TABLE IF EXISTS `reglement`;
CREATE TABLE IF NOT EXISTS `reglement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_reglement` date DEFAULT NULL,
  `mode_de_paiement` varchar(50) DEFAULT NULL,
  `id_facture` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_facture` (`id_facture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

DROP TABLE IF EXISTS `session`;
CREATE TABLE IF NOT EXISTS `session` (
  `id_session` int NOT NULL AUTO_INCREMENT,
  `description` text,
  `date_heure` datetime DEFAULT NULL,
  `prix_session` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id_session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `congressiste`
--
ALTER TABLE `congressiste`
  ADD CONSTRAINT `congressiste_ibfk_1` FOREIGN KEY (`id_organisme`) REFERENCES `organisme_payeur` (`id_organisme`),
  ADD CONSTRAINT `congressiste_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`);

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `facture_ibfk_1` FOREIGN KEY (`id_organisme`) REFERENCES `organisme_payeur` (`id_organisme`),
  ADD CONSTRAINT `facture_ibfk_2` FOREIGN KEY (`id_congressiste`) REFERENCES `congressiste` (`id_congressiste`);

--
-- Contraintes pour la table `participation_activite`
--
ALTER TABLE `participation_activite`
  ADD CONSTRAINT `participation_activite_ibfk_1` FOREIGN KEY (`id_congressiste`) REFERENCES `congressiste` (`id_congressiste`),
  ADD CONSTRAINT `participation_activite_ibfk_2` FOREIGN KEY (`id_activite`) REFERENCES `activite` (`id_activite`);

--
-- Contraintes pour la table `participation_session`
--
ALTER TABLE `participation_session`
  ADD CONSTRAINT `participation_session_ibfk_1` FOREIGN KEY (`id_congressiste`) REFERENCES `congressiste` (`id_congressiste`),
  ADD CONSTRAINT `participation_session_ibfk_2` FOREIGN KEY (`id_session`) REFERENCES `session` (`id_session`);

--
-- Contraintes pour la table `reglement`
--
ALTER TABLE `reglement`
  ADD CONSTRAINT `reglement_ibfk_1` FOREIGN KEY (`id_facture`) REFERENCES `facture` (`id_facture`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
