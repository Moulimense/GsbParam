<?php
/** * Mission : architecture MVC GsbParam
 * * @file ModeleFront.php
 * @author Marielle Jouin <jouin.marielle@gmail.com>
 * @version    3.0
 * @details contient les fonctions d'accès BD pour le FrontEnd
 */
require_once 'modele/Modele.php';

/**
 * @class ModeleFront
 * @brief contient les fonctions d'accès aux infos de la BD pour les utilisateurs
 */
class ModeleFront extends Modele
{

	/**
	 * Retourne toutes les catégories 
	 *
	 * @return array $lesLignes le tableau des catégories (tableau d'objets)
	 */
	public function getLesCategories()
	{
		try {
			$req = 'select id, libelle from categorie';
			$res = $this->executerRequete($req);
			$lesLignes = $res->fetchAll(PDO::FETCH_OBJ);
			return $lesLignes;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * Retourne toutes les informations d'une catégorie passée en paramètre
	 *
	 * @param string $idCategorie l'id de la catégorie
	 * @return object $laLigne la catégorie (objet)
	 */
	public function getLesInfosCategorie($idCategorie)
	{
		try {
			// Utilisation d'une requête préparée pour sécuriser l'accès
			$req = 'SELECT id, libelle FROM categorie WHERE id = ?';
			$res = $this->executerRequete($req, array($idCategorie));
			$laLigne = $res->fetch(PDO::FETCH_OBJ);
			return $laLigne;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * Retourne sous forme d'un tableau tous les produits de la
	 * catégorie passée en argument (si null retourne tous les produits)
	 * * @param string $idCategorie l'id de la catégorie dont on veut les produits
	 * @return array $lesLignes un tableau des produits
	 */
	public function getLesProduitsDeCategorie($idCategorie = null)
	{
		try {
			if ($idCategorie == null) {
				$req = 'select id, description, prix, image, idCategorie from produit';
				$res = $this->executerRequete($req);
			} else {
				// Requête préparée pour sécuriser le filtrage par catégorie
				$req = 'select id, description, prix, image, idCategorie from produit where idCategorie = ?';
				$res = $this->executerRequete($req, array($idCategorie));
			}
			$lesLignes = $res->fetchAll(PDO::FETCH_OBJ);
			return $lesLignes;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * Retourne les produits concernés par le tableau des idProduits passé en argument
	 *
	 * @param array $desIdsProduit tableau d'idProduits
	 * @return array $lesProduits un tableau contenant les infos des produits
	 */
	public function getLesProduitsDuTableau($desIdsProduit = null)
	{
		try {
			$lesProduits = array();
			if ($desIdsProduit != null) {
				foreach ($desIdsProduit as $unIdProduit) {
					// Sécurisation de la récupération individuelle de chaque produit
					$req = 'select id, description, prix, image, idCategorie from produit where id = ?';
					$res = $this->executerRequete($req, array($unIdProduit));
					$unProduit = $res->fetch(PDO::FETCH_OBJ);
					$lesProduits[] = $unProduit;
				}
			} else {
				$req = 'select id, description, prix, image, idCategorie from produit';
				$res = $this->executerRequete($req);
				$lesProduits = $res->fetchAll(PDO::FETCH_OBJ);
			}
			return $lesProduits;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * Crée une commande de façon sécurisée (Requêtes préparées)
	 *
	 * @param string $nom nom du client
	 * @param string $rue rue du client
	 * @param string $cp cp du client
	 * @param string $ville ville du client
	 * @param string $mail mail du client
	 * @param array $lesProduitsQte tableau contenant les id des produits en clé et les quantités en valeur
	 * @return boolean true si succès, false si erreur
	 */
	public function creerCommande($nom, $rue, $cp, $ville, $mail, $lesProduitsQte) {
		try {
			$req = 'select max(id) as maxi from commande';
			$res = $this->executerRequete($req);
			$laLigne = $res->fetch();
			$idCommande = $laLigne['maxi'] + 1;
			$date = date('Y-m-d');

			// Insertion de la commande
			$req = "insert into commande (id, dateCommande, nomPrenomClient, adresseRueClient, cpClient, villeClient, mailClient) values (?, ?, ?, ?, ?, ?, ?)";
			$this->executerRequete($req, array($idCommande, $date, $nom, $rue, $cp, $ville, $mail));

			// Insertion des produits avec leur quantité
			foreach ($lesProduitsQte as $idProduit => $qte) {
				$req = "insert into contenir (idCommande, idProduit, quantite) values (?, ?, ?)";
				$this->executerRequete($req, array($idCommande, $idProduit, $qte));
			}
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

    /**
     * Retourne les informations d'un administrateur
     */
    public function getInfosAdmin($login) {
        $req = "select id, nom, mdp from administrateur where nom = ?";
        $res = $this->executerRequete($req, array($login));
        return $res->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Vérifie les identifiants client et retourne ses infos
     * @param string $mail
     * @param string $mdp
     * @return object|false infos du client ou false si erreur
     */
    public function getInfosClient($mail, $mdp) {
        // Dans l'idéal, on récupère le mdp haché et on utilise password_verify
        $req = "select id, nom, prenom, rue, cp, ville, mail from client where mail = ? and mdp = ?";
        $res = $this->executerRequete($req, array($mail, $mdp));
        return $res->fetch(PDO::FETCH_OBJ);
    }
}
?>