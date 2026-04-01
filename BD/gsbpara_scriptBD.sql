-- phpMyAdmin SQL Dump
-- version 5.0.2
-- Généré le : 06 sep. 2024 à 17:27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  gsbparam
--

-- --------------------------------------------------------

--
-- Structure de la table administrateur
--

DROP TABLE IF EXISTS administrateur;
CREATE TABLE IF NOT EXISTS administrateur (
  `id` char(3) NOT NULL,
  `nom` varchar(50)  NOT NULL,
  `mdp` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8MB4 ;

--
-- Contenu de la table administrateur supprimé du script pour des raisons de sécurité : il vous est indiqué dans le texte du TP
--


-- --------------------------------------------------------

--
-- Structure de la table categorie
--

DROP TABLE IF EXISTS categorie;
CREATE TABLE IF NOT EXISTS categorie (
  `id` char(3) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4 ;


--
-- Contenu de la table administrateur
--

INSERT INTO administrateur (id, nom, mdp) VALUES
('1', 'LeBoss', 'TheBest$147#'),
('2', 'LeChefProjet', 'NearlyTheBest$280@');
--
-- Contenu de la table categorie
--

INSERT INTO categorie (id, libelle) VALUES
('CH', 'Cheveux'),
('FO', 'Forme'),
('PS', 'Protection Solaire');

-- --------------------------------------------------------

--
-- Structure de la table commande
--

DROP TABLE IF EXISTS commande;
CREATE TABLE IF NOT EXISTS commande (
  `id` varchar(32) NOT NULL,
  `dateCommande` date,
  `nomPrenomClient` varchar(50),
  `adresseRueClient` varchar(50),
  `cpClient` char(5),
  `villeClient` varchar(50),
  `mailClient` varchar(50),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4 ;

--
-- Contenu de la table commande
--

INSERT INTO commande (id, dateCommande, nomPrenomClient, adresseRueClient, cpClient, villeClient, mailClient) VALUES
('1101461660', '2024-09-01', 'Dupont Jacques', '12, rue haute', '75001', 'Paris', 'dupont@wanadoo.fr'),
('1101461665', '2024-09-01', 'Durant Yves', '23, rue des ombres', '75012', 'Paris', 'durant@free.fr');

-- --------------------------------------------------------

--
-- Structure de la table contenir
--

DROP TABLE IF EXISTS contenir;
CREATE TABLE IF NOT EXISTS contenir (
  `idCommande` varchar(32)  NOT NULL,
  `idProduit` varchar(5) NOT NULL,
  PRIMARY KEY (`idCommande`,`idProduit`),
  KEY `I_FK_CONTENIR_COMMANDE` (`idCommande`),
  KEY `I_FK_CONTENIR_Produit` (`idProduit`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4 ;

--
-- Contenu de la table contenir
--

INSERT INTO contenir (idCommande, idProduit) VALUES
('1101461660', 'f03'),
('1101461660', 'p01'),
('1101461665', 'f05'),
('1101461665', 'p06');

-- --------------------------------------------------------

--
-- Structure de la table produit
--

DROP TABLE IF EXISTS produit;
CREATE TABLE IF NOT EXISTS produit (
  `id` varchar(5) NOT NULL,
  `description` char(50) ,
  `prix` decimal(10,2) ,
  `image` char(100) ,
  `idCategorie` char(3) ,
  PRIMARY KEY (`id`),
  KEY `I_FK_Produit_CATEGORIE` (`idCategorie`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4 ;

--
-- Contenu de la table produit
--

INSERT INTO produit (id, description, prix, image, idCategorie) VALUES
('c01', 'Laino Shampooing Douche au Thé Vert BIO', '4.00', 'assets/images/laino-shampooing-douche-au-the-vert-bio-200ml.png', 'CH'),
('c02', 'Klorane fibres de lin baume après shampooing', '10.80', 'assets/images/klorane-fibres-de-lin-baume-apres-shampooing-150-ml.jpg', 'CH'),
('c03', 'Weleda Kids 2in1 Shower & Shampoo Orange fruitée', '4.00', 'assets/images/weleda-kids-2in1-shower-shampoo-orange-fruitee-150-ml.jpg', 'CH'),
('c04', 'Weleda Kids 2in1 Shower & Shampoo vanille douce', '4.00', 'assets/images/weleda-kids-2in1-shower-shampoo-vanille-douce-150-ml.jpg', 'CH'),
('c05', 'Klorane Shampooing sec à l''extrait d''ortie', '6.10', 'assets/images/klorane-shampooing-sec-a-l-extrait-d-ortie-spray-150ml.png', 'CH'),
('c06', 'Phytopulp mousse volume intense', '18.00', 'assets/images/phytopulp-mousse-volume-intense-200ml.jpg', 'CH'),
('c07', 'Bio Beaute by Nuxe Shampooing nutritif', '8.00', 'assets/images/bio-beaute-by-nuxe-shampooing-nutritif-200ml.png', 'CH'),
('f01', 'Nuxe Men Contour des Yeux Multi-Fonctions', '12.05', 'assets/images/nuxe-men-contour-des-yeux-multi-fonctions-15ml.png', 'FO'),
('f02', 'Tisane romon nature sommirel bio sachet 20', '5.50', 'assets/images/tisane-romon-nature-sommirel-bio-sachet-20.jpg', 'FO'),
('f03', 'La Roche Posay Cicaplast crème pansement', '11.00', 'assets/images/la-roche-posay-cicaplast-creme-pansement-40ml.jpg', 'FO'),
('f04', 'Futuro sport stabilisateur pour cheville', '26.50', 'assets/images/futuro-sport-stabilisateur-pour-cheville-deluxe-attelle-cheville.png', 'FO'),
('f05', 'Microlife pèse-personne électronique weegschaal', '63.00', 'assets/images/microlife-pese-personne-electronique-weegschaal-ws80.jpg', 'FO'),
('f06', 'Melapi Miel Thym Liquide 500g', '6.50', 'assets/images/melapi-miel-thym-liquide-500g.jpg', 'FO'),
('f07', 'Meli Meliflor Pollen 200g', '8.60', 'assets/images/melapi-pollen-250g.jpg', 'FO'),
('p01', 'Avène solaire Spray très haute protection', '22.00', 'assets/images/avene-solaire-spray-tres-haute-protection-spf50200ml.png', 'PS'),
('p02', 'Mustela Solaire Lait très haute Protection', '17.50', 'assets/images/mustela-solaire-lait-tres-haute-protection-spf50-100ml.jpg', 'PS'),
('p03', 'Isdin Eryfotona aAK fluid', '29.00', 'assets/images/isdin-eryfotona-aak-fluid-100-50ml.jpg', 'PS'),
('p04', 'La Roche Posay Anthélios 50+ Brume Visage', '8.75', 'assets/images/la-roche-posay-anthelios-50-brume-visage-toucher-sec-75ml.png', 'PS'),
('p05', 'Nuxe Sun Huile Lactée Capillaire Protectrice', '15.00', 'assets/images/nuxe-sun-huile-lactee-capillaire-protectrice-100ml.png', 'PS'),
('p06', 'Uriage Bariésun stick lèvres SPF30 4g', '5.65', 'assets/images/uriage-bariesun-stick-levres-spf30-4g.jpg', 'PS'),
('p07', 'Bioderma Cicabio creme SPF50+ 30ml', '13.70', 'assets/images/bioderma-cicabio-creme-spf50-30ml.png', 'PS');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table contenir
--
ALTER TABLE contenir
  ADD CONSTRAINT `contenir_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES commande (`id`),
  ADD CONSTRAINT `contenir_ibfk_2` FOREIGN KEY (`idProduit`) REFERENCES produit (`id`);

--
-- Contraintes pour la table produit
--
ALTER TABLE produit
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`idCategorie`) REFERENCES categorie (`id`);


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;