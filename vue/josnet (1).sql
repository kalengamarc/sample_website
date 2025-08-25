-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 25, 2025 at 12:14 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `josnet`
--

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id_commande` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `date_commande` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en attente','payée','expédiée','livrée','annulée') DEFAULT 'en attente',
  `montant_total` decimal(10,2) DEFAULT NULL,
  `id_produit` int NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `fk_cmd_user` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `details_commandes`
--

DROP TABLE IF EXISTS `details_commandes`;
CREATE TABLE IF NOT EXISTS `details_commandes` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_commande` int DEFAULT NULL,
  `id_produit` int DEFAULT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `fk_det_cmd` (`id_commande`),
  KEY `fk_det_produit` (`id_produit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `formations`
--

DROP TABLE IF EXISTS `formations`;
CREATE TABLE IF NOT EXISTS `formations` (
  `id_formation` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `duree` varchar(50) DEFAULT NULL,
  `id_formateur` int DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_formation`),
  KEY `fk_formateur` (`id_formateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inscriptions`
--

DROP TABLE IF EXISTS `inscriptions`;
CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id_inscription` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `id_formation` int DEFAULT NULL,
  `statut` enum('inscrit','en cours','terminé','annulé') DEFAULT 'inscrit',
  `date_inscription` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_inscription`),
  KEY `fk_ins_user` (`id_utilisateur`),
  KEY `fk_ins_form` (`id_formation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `id_paiement` int NOT NULL AUTO_INCREMENT,
  `id_utilisateur` int DEFAULT NULL,
  `type` enum('formation','commande') NOT NULL,
  `id_reference` int NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `mode` enum('carte','mobile_money','paypal','virement') NOT NULL,
  `statut` enum('en attente','réussi','échoué') DEFAULT 'en attente',
  `date_paiement` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_paiement`),
  KEY `fk_paie_user` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presences`
--

DROP TABLE IF EXISTS `presences`;
CREATE TABLE IF NOT EXISTS `presences` (
  `id_presence` int NOT NULL AUTO_INCREMENT,
  `id_inscription` int DEFAULT NULL,
  `date_session` date DEFAULT NULL,
  `statut` enum('present','absent') NOT NULL,
  PRIMARY KEY (`id_presence`),
  KEY `fk_pres_ins` (`id_inscription`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id_produit` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_produit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `role` enum('admin','formateur','etudiant','client') DEFAULT 'client',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `telephone`, `role`, `date_creation`) VALUES
(1, 'Durand', 'Jean', 'jean.dupont@example.com', 'xxxxx', '0601020304', 'admin', '2025-08-25 12:12:55');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `fk_cmd_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `details_commandes`
--
ALTER TABLE `details_commandes`
  ADD CONSTRAINT `fk_det_cmd` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_det_produit` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `formations`
--
ALTER TABLE `formations`
  ADD CONSTRAINT `fk_formateur` FOREIGN KEY (`id_formateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `fk_ins_form` FOREIGN KEY (`id_formation`) REFERENCES `formations` (`id_formation`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ins_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `fk_paie_user` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `presences`
--
ALTER TABLE `presences`
  ADD CONSTRAINT `fk_pres_ins` FOREIGN KEY (`id_inscription`) REFERENCES `inscriptions` (`id_inscription`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
