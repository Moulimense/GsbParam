-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : mer. 01 avr. 2026 à 15:09
-- Version du serveur : 11.5.2-MariaDB
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gsb_paramv2`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

DROP TABLE IF EXISTS `administrateur`;
CREATE TABLE IF NOT EXISTS `administrateur` (
  `id` char(3) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `mdp` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`id`, `nom`, `mdp`) VALUES
('1', 'LeBoss', 'TheBest$147#'),
('2', 'LeChefProjet', 'NearlyTheBest$280@');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` char(3) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `libelle`) VALUES
('CH', 'Cheveux'),
('FO', 'Forme'),
('PS', 'Protection Solaire');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `rue` varchar(100) NOT NULL,
  `cp` char(5) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id` varchar(32) NOT NULL,
  `dateCommande` date DEFAULT NULL,
  `nomPrenomClient` varchar(50) DEFAULT NULL,
  `adresseRueClient` varchar(50) DEFAULT NULL,
  `cpClient` char(5) DEFAULT NULL,
  `villeClient` varchar(50) DEFAULT NULL,
  `mailClient` varchar(50) DEFAULT NULL,
  `idClient` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_commande_client` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id`, `dateCommande`, `nomPrenomClient`, `adresseRueClient`, `cpClient`, `villeClient`, `mailClient`, `idClient`) VALUES
('1101461660', '2024-09-01', 'Dupont Jacques', '12, rue haute', '75001', 'Paris', 'dupont@wanadoo.fr', NULL),
('1101461665', '2024-09-01', 'Durant Yves', '23, rue des ombres', '75012', 'Paris', 'durant@free.fr', NULL),
('1101461666', '2026-03-31', 'uyhiuyo', 'qdgfdc', '22222', 'dcbds', 'fve@gmail.com', NULL),
('1101461667', '2026-04-01', 'fshgsdf,fs', 'f,bnf,ggf gf', '46152', 'vojlyfeyrhjl;df', 'sol@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `contenir`
--

DROP TABLE IF EXISTS `contenir`;
CREATE TABLE IF NOT EXISTS `contenir` (
  `idCommande` varchar(32) NOT NULL,
  `idProduit` varchar(5) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idCommande`,`idProduit`),
  KEY `I_FK_CONTENIR_COMMANDE` (`idCommande`),
  KEY `I_FK_CONTENIR_Produit` (`idProduit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `contenir`
--

INSERT INTO `contenir` (`idCommande`, `idProduit`, `quantite`) VALUES
('1101461660', 'f03', 1),
('1101461660', 'p01', 1),
('1101461665', 'f05', 1),
('1101461665', 'p06', 1),
('1101461666', 'c01', 3),
('1101461667', 'c02', 7);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` varchar(5) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `idCategorie` char(3) DEFAULT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `contenance` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `noteClient` float DEFAULT 4,
  PRIMARY KEY (`id`),
  KEY `I_FK_Produit_CATEGORIE` (`idCategorie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `description`, `prix`, `image`, `idCategorie`, `marque`, `contenance`, `stock`, `noteClient`) VALUES
('c01', 'Laino Shampooing Douche au Thé Vert BIO', 4.00, 'assets/images/laino-shampooing-douche-au-the-vert-bio-200ml.png', 'CH', 'Laino', '200 ml', 15, 4.5),
('c02', 'Klorane fibres de lin baume après shampooing', 10.80, 'assets/images/klorane-fibres-de-lin-baume-apres-shampooing-150-ml.jpg', 'CH', 'Klorane', '150 ml', 5, 4.8),
('c03', 'Weleda Kids 2in1 Shower & Shampoo Orange fruitée', 4.00, 'assets/images/weleda-kids-2in1-shower-shampoo-orange-fruitee-150-ml.jpg', 'CH', 'Weleda', '150 ml', 20, 4.2),
('c04', 'Weleda Kids 2in1 Shower & Shampoo vanille douce', 4.00, 'assets/images/weleda-kids-2in1-shower-shampoo-vanille-douce-150-ml.jpg', 'CH', 'Weleda', '150 ml', 18, 4.5),
('c05', 'Klorane Shampooing sec à l\'extrait d\'ortie', 6.10, 'assets/images/klorane-shampooing-sec-a-l-extrait-d-ortie-spray-150ml.png', 'CH', 'Klorane', '150 ml', 7, 4),
('c06', 'Phytopulp mousse volume intense', 18.00, 'assets/images/phytopulp-mousse-volume-intense-200ml.jpg', 'CH', 'Phyto', '200 ml', 22, 4.6),
('c07', 'Bio Beaute by Nuxe Shampooing nutritif', 8.00, 'assets/images/bio-beaute-by-nuxe-shampooing-nutritif-200ml.png', 'CH', 'Nuxe', '200 ml', 11, 4.6),
('f01', 'Nuxe Men Contour des Yeux Multi-Fonctions', 12.05, 'assets/images/nuxe-men-contour-des-yeux-multi-fonctions-15ml.png', 'FO', 'Nuxe Men', '15 ml', 10, 4.3),
('f02', 'Tisane romon nature sommirel bio sachet 20', 5.50, 'assets/images/tisane-romon-nature-sommirel-bio-sachet-20.jpg', 'FO', 'Romon Nature', '20 sachets', 15, 4.9),
('f03', 'La Roche Posay Cicaplast crème pansement', 11.00, 'assets/images/la-roche-posay-cicaplast-creme-pansement-40ml.jpg', 'FO', 'La Roche Posay', '40 ml', 31, 4.1),
('f04', 'Futuro sport stabilisateur pour cheville', 26.50, 'assets/images/futuro-sport-stabilisateur-pour-cheville-deluxe-attelle-cheville.png', 'FO', 'Futuro', 'Taille Unique', 18, 3.5),
('f05', 'Microlife pèse-personne électronique weegschaal', 63.00, 'assets/images/microlife-pese-personne-electronique-weegschaal-ws80.jpg', 'FO', 'Microlife', 'Unité', 27, 4.5),
('f06', 'Melapi Miel Thym Liquide 500g', 6.50, 'assets/images/melapi-miel-thym-liquide-500g.jpg', 'FO', 'Melapi', '500 g', 10, 4.7),
('f07', 'Meli Meliflor Pollen 200g', 8.60, 'assets/images/melapi-pollen-250g.jpg', 'FO', 'Meli', '200 g', 23, 4.3),
('p01', 'Avène solaire Spray très haute protection', 22.00, 'assets/images/avene-solaire-spray-tres-haute-protection-spf50200ml.png', 'PS', 'Avène', '200 ml', 8, 4.6),
('p02', 'Mustela Solaire Lait très haute Protection', 17.50, 'assets/images/mustela-solaire-lait-tres-haute-protection-spf50-100ml.jpg', 'PS', 'Mustela', '100 ml', 34, 3.8),
('p03', 'Isdin Eryfotona aAK fluid', 29.00, 'assets/images/isdin-eryfotona-aak-fluid-100-50ml.jpg', 'PS', 'Isdin', '50 ml', 14, 4.6),
('p04', 'La Roche Posay Anthélios 50+ Brume Visage', 8.75, 'assets/images/la-roche-posay-anthelios-50-brume-visage-toucher-sec-75ml.png', 'PS', 'La Roche Posay', '75 ml', 32, 3.9),
('p05', 'Nuxe Sun Huile Lactée Capillaire Protectrice', 15.00, 'assets/images/nuxe-sun-huile-lactee-capillaire-protectrice-100ml.png', 'PS', 'Nuxe Sun', '100 ml', 29, 3.6),
('p06', 'Uriage Bariésun stick lèvres SPF30 4g', 5.65, 'assets/images/uriage-bariesun-stick-levres-spf30-4g.jpg', 'PS', 'Uriage', '4 g', 9, 4),
('p07', 'Bioderma Cicabio creme SPF50+ 30ml', 13.70, 'assets/images/bioderma-cicabio-creme-spf50-30ml.png', 'PS', 'Bioderma', '30 ml', 12, 4.7);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_client` FOREIGN KEY (`idClient`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `contenir`
--
ALTER TABLE `contenir`
  ADD CONSTRAINT `contenir_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `contenir_ibfk_2` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`idCategorie`) REFERENCES `categorie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
