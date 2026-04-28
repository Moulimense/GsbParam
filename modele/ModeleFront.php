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
	 * @param string $idCategorie l'id de la catégorie
	 * @return object $laLigne la catégorie (objet)
	 */
	public function getLesInfosCategorie($idCategorie)
	{
		try {
			$req = 'SELECT id, libelle FROM categorie WHERE id = ?';
			$res = $this->executerRequete($req, array($idCategorie));
			$laLigne = $res->fetch(PDO::FETCH_OBJ);
			return $laLigne;
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * Retourne les produits en fonction des filtres (Évolution Phase 2)
	 * Protégé contre les injections SQL via requêtes préparées.
	 */
	public function getLesProduitsFiltres($idCategorie = null, $prixMin = null, $prixMax = null, $marque = null)
	{
		try {
			// Construction dynamique de la requête
			$sql = "SELECT id, description, prix, image, idCategorie, marque FROM produit WHERE 1=1";
			$params = array();

			if ($idCategorie && $idCategorie != 'tous') {
				$sql .= " AND idCategorie = ?";
				$params[] = $idCategorie;
			}
			if ($prixMin) {
				$sql .= " AND prix >= ?";
				$params[] = $prixMin;
			}
			if ($prixMax) {
				$sql .= " AND prix <= ?";
				$params[] = $prixMax;
			}
			if ($marque && $marque != '') {
				$sql .= " AND marque = ?";
				$params[] = $marque;
			}

			$sql .= " ORDER BY prix ASC";
			$res = $this->executerRequete($sql, $params);
			return $res->fetchAll(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			// Si l'erreur est "Column not found", on retourne une liste vide ou gérée
			return array();
		}
	}

	/**
	 * Retourne la liste unique des marques présentes en base
	 */
	public function getLesMarques()
	{
		try {
			$req = "SELECT DISTINCT marque FROM produit WHERE marque IS NOT NULL ORDER BY marque";
			$res = $this->executerRequete($req);
			return $res->fetchAll(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			return array();
		}
	}

	/**
	 * Retourne les produits concernés par le tableau des idProduits passé en argument
	 */
	public function getLesProduitsDuTableau($desIdsProduit = null)
	{
		try {
			$lesProduits = array();
			if ($desIdsProduit != null) {
				foreach ($desIdsProduit as $unIdProduit) {
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
	 * Retourne les informations complètes d'un produit
	 */
	public function getInfosProduit($id)
	{
		try {
			$req = 'select id, description, prix, image, idCategorie, noteClient, marque, contenance, stock from produit where id = ?';
			$res = $this->executerRequete($req, array($id));
			return $res->fetch(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * Retourne des produits associés de la même catégorie (Cross-selling)
	 */
	public function getProduitsAssocies($idCategorie, $idProduit, $limite = 3)
	{
		try {
			$req = 'select id, description, prix, image, idCategorie from produit where idCategorie = ? and id != ? order by rand() limit ' . (int) $limite;
			$res = $this->executerRequete($req, array($idCategorie, $idProduit));
			return $res->fetchAll(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			return false;
		}
	}

	/**
	 * Crée une commande
	 */
	public function creerCommande($nom, $rue, $cp, $ville, $mail, $lesProduitsQte)
	{
		try {
			$req = 'select max(id) as maxi from commande';
			$res = $this->executerRequete($req);
			$laLigne = $res->fetch();
			$idCommande = $laLigne['maxi'] + 1;
			$date = date('Y-m-d');

			$req = "insert into commande (id, dateCommande, nomPrenomClient, adresseRueClient, cpClient, villeClient, mailClient) values (?, ?, ?, ?, ?, ?, ?)";
			$this->executerRequete($req, array($idCommande, $date, $nom, $rue, $cp, $ville, $mail));

			foreach ($lesProduitsQte as $idProduit => $qte) {
				$req = "insert into contenir (idCommande, idProduit, quantite) values (?, ?, ?)";
				$this->executerRequete($req, array($idCommande, $idProduit, $qte));
			}
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function getInfosAdmin($login)
	{
		$req = "select id, nom, mdp from administrateur where nom = ?";
		$res = $this->executerRequete($req, array($login));
		return $res->fetch(PDO::FETCH_OBJ);
	}

	public function creerProduit($id, $description, $prix, $image, $idCategorie)
	{
		$req = "INSERT INTO produit (id, description, prix, image, idCategorie) VALUES (?, ?, ?, ?, ?)";
		$this->executerRequete($req, array($id, $description, $prix, $image, $idCategorie));
	}

	public function modifierProduit($id, $description, $prix, $idCategorie)
	{
		$req = "UPDATE produit SET description = ?, prix = ?, idCategorie = ? WHERE id = ?";
		$this->executerRequete($req, array($description, $prix, $idCategorie, $id));
	}

	public function supprimerProduit($id)
	{
		$req = "DELETE FROM produit WHERE id = ?";
		$this->executerRequete($req, array($id));
	}

	public function getTousLesProduits()
	{
		$req = "SELECT id, description, prix, image, idCategorie FROM produit";
		$res = $this->executerRequete($req);
		return $res->fetchAll(PDO::FETCH_OBJ);
	}

	public function inscrireClient($nom, $prenom, $rue, $cp, $ville, $mail, $mdp)
	{
		$mdpHash = password_hash($mdp, PASSWORD_BCRYPT);
		$req = "INSERT INTO client (nom, prenom, rue, cp, ville, mail, mdp) 
            VALUES (:nom, :prenom, :rue, :cp, :ville, :mail, :mdp)";
		try {
			$this->executerRequete($req, array(
				':nom' => $nom,
				':prenom' => $prenom,
				':rue' => $rue,
				':cp' => $cp,
				':ville' => $ville,
				':mail' => $mail,
				':mdp' => $mdpHash
			));
			return true;
		} catch (PDOException $e) {
			return false;
		}
	}

	public function verifierClient($mail, $mdp)
	{
		$req = "SELECT id, nom, prenom, mdp FROM client WHERE mail = ?";
		try {
			$res = $this->executerRequete($req, array($mail));
			$client = $res->fetch(PDO::FETCH_OBJ);
			if ($client && password_verify($mdp, $client->mdp)) {
				return $client;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			return false;
		}
	}

	public function getInfosClient($id)
	{
		$req = "SELECT id, nom, prenom, rue, cp, ville, mail FROM client WHERE id = ?";
		try {
			$res = $this->executerRequete($req, array($id));
			return $res->fetch(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			return false;
		}
	}

	public function estMailUtilise($mail)
	{
		$req = "SELECT COUNT(*) AS nb FROM client WHERE mail = ?";
		try {
			$res = $this->executerRequete($req, array($mail));
			$ligne = $res->fetch(PDO::FETCH_OBJ);
			return ($ligne->nb > 0);
		} catch (PDOException $e) {
			return false;
		}
	}
}
?>