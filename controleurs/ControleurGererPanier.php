<?php
/**
 * Mission GsbParam PHP Objet
 * 
 * @file ControleurGererPanier.php
 * @author Marielle Jouin <jouin.marielle@gmail.com>
 * @version    3.0
 * @brief contient les fonctions pour gérer le panier
 *
 * regroupe les fonctions pour gérer le panier, et les erreurs de saisie dans le formulaire de commande
 */
/**
 * @class ControleurGererPanier
 * @brief contient les fonctions pour gérer le panier
 */
class ControleurGererPanier
{
	private $modeleFront;

	public function __construct()
	{
		$this->modeleFront = new ModeleFront();
		$this->initPanier();
	}

	/**
	 * Initialise le panier
	 *
	 * Crée un tableau $_SESSION['produits'] en session dans le cas
	 * où il n'existe pas déjà
	 */
	function initPanier()
	{
		if (!isset($_SESSION['produits'])) {
			$_SESSION['produits'] = array();
		}
	}

	/**
	 * Voir le panier
	 *
	 * permet d'afficher les produits contenus dans le panier
	 * leur descriptif est récupéré grâce à chaque id par getLesProduitsDuTableau()
	 */
	function voirPanier()
	{
		$n = $this->nbProduitsDuPanier();
		if ($n > 0) {
			$desIdProduit = $this->getLesIdProduitsDuPanier();
			$lesProduitsDuPanier = $this->modeleFront->getLesProduitsDuTableau($desIdProduit);
			include("vues/v_panier.php");
		} else {
			if (!isset($_SESSION['idClient'])) {
				$message = "Le panier est vide ! Vous devez vous connecter/inscrire pour pouvoir commander des articles.";
			} else {
				$message = "Le panier est vide !";
			}
			include("vues/v_message.php");
		}
	}

	/**
	 * Vide le panier
	 *
	 * Supprime le tableau $_SESSION['produits'] et affiche le panier
	 */
	function viderPanier()
	{
		unset($_SESSION['produits']);
		$this->voirPanier();
	}

	/**
	 * Retire un produit du panier
	 *
	 * @param string $idProduit
	 */
	public function retirerDuPanier($idProduit)
	{
		if (isset($_SESSION['produits'][$idProduit])) {
			// On supprime la clé du tableau associatif
			unset($_SESSION['produits'][$idProduit]);
		}
		// On réaffiche le panier mis à jour
		$this->voirPanier();
	}

	/**
	 * Ajoute un produit au panier
	 *
	 * Teste si le produit est déjà dans la variable session 
	 * ajoute le produit à la variable de session dans le cas où
	 * où le produit n'a pas été trouvé
	 * 
	 * @param string $idProduit Le produit à ajouter au panier 
	 */
	function ajouterAuPanier($idProduit)
	{
		$produit = $this->modeleFront->getInfosProduit($idProduit);
		$stock = $produit ? $produit->stock : 0;
		$qteActuelle = isset($_SESSION['produits'][$idProduit]) ? $_SESSION['produits'][$idProduit] : 0;
		$quantiteAjoutee = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 1;
		$qteDesiree = $qteActuelle + $quantiteAjoutee;

		if ($qteDesiree > $stock) {
			$msgErreurs[] = "Erreur : La quantité demandée dépasse le stock disponible (" . $stock . " restant en stock).";
			include("vues/v_erreurs.php");
		} else {
			$_SESSION['produits'][$idProduit] = $qteDesiree;
		}
		$this->voirPanier();
	}

	function modifierQuantite($idProduit, $qte)
	{
		$produit = $this->modeleFront->getInfosProduit($idProduit);
		$stock = $produit ? $produit->stock : 0;

		if ($qte <= 0) {
			unset($_SESSION['produits'][$idProduit]);
		} else if ($qte > $stock) {
			$msgErreurs[] = "Erreur : La quantité demandée dépasse le stock disponible (" . $stock . " restant en stock).";
			include("vues/v_erreurs.php");
		} else {
			$_SESSION['produits'][$idProduit] = $qte;
		}
		$this->voirPanier();
	}

	/**
	 * Retourne les produits du panier
	 *
	 * Retourne le tableau des identifiants de modeleFront
	 * 
	 * @return array $_SESSION['produits'] le tableau des id produits du panier 
	 */
	function getLesIdProduitsDuPanier()
	{
		return array_keys($_SESSION['produits']);
	}

	/**
	 * Retourne le tableau associatif des produits du panier avec leur quantité
	 * 
	 * @return array $_SESSION['produits'] le tableau idProduit => quantite
	 */
	function getLesProduitsQteDuPanier()
	{
		return $_SESSION['produits'];
	}

	/**
	 * Retourne le nombre de produits du panier
	 *
	 * Teste si la variable de session existe
	 * et retourne le nombre d'éléments de la variable session
	 * 
	 * @return int 
	 */
	function nbProduitsDuPanier()
	{
		return isset($_SESSION['produits']) ? count($_SESSION['produits']) : 0;
	}

	function passerCommande()
	{
		if (!isset($_SESSION['idClient'])) {
			include("vues/v_acces_restreint.php");
			return;
		}

		if ($this->nbProduitsDuPanier() > 0) {
			$client = $this->modeleFront->getInfosClient($_SESSION['idClient']);

			if ($client) {
				$nom = $client->nom . ' ' . $client->prenom;
				$rue = $client->rue;
				$ville = $client->ville;
				$cp = $client->cp;
				$mail = $client->mail;

				$lesProduitsQte = $this->getLesProduitsQteDuPanier();
				$exec = $this->modeleFront->creerCommande($nom, $rue, $cp, $ville, $mail, $lesProduitsQte);

				if ($exec) {
					$message = "La commande a été enregistrée avec succès. Merci de votre visite !";
					unset($_SESSION['produits']);
					include("vues/v_message.php");
				} else {
					$msgErreurs[] = "Erreur technique : La commande n'a pas pu être enregistrée en base de données.";
					include("vues/v_erreurs.php");
				}
			} else {
				$msgErreurs[] = "Erreur : Impossible de récupérer les informations de votre compte.";
				include("vues/v_erreurs.php");
			}
		} else {
			$message = "Votre panier est vide !";
			include("vues/v_message.php");
		}
	}

	/**
	 * Traite les informations du formulaire de commande
	 *
	 * si les informations sont OK : enregistre la commande et son contenu
	 * sinon affiche les erreurs de saisie et le formulaire vide
	 */
	function confirmerCommande()
	{
		$nom = $_REQUEST['nom'];
		$rue = $_REQUEST['rue'];
		$ville = $_REQUEST['ville'];
		$cp = $_REQUEST['cp'];
		$mail = $_REQUEST['mail'];
		$msgErreurs = $this->getErreursSaisieCommande($nom, $rue, $ville, $cp, $mail);

		if (count($msgErreurs) != 0) {
			include("vues/v_erreurs.php");
			include("vues/v_commande.php");
		} else {
			$lesProduitsQte = $this->getLesProduitsQteDuPanier();
			$exec = $this->modeleFront->creerCommande($nom, $rue, $cp, $ville, $mail, $lesProduitsQte);

			if ($exec) {
				$message = "La commande a été enregistrée. Merci de votre visite.";
				unset($_SESSION['produits']);
				include("vues/v_message.php");
			} else {
				$msgErreurs[] = "Erreur technique : La commande n'a pas pu être enregistrée en base de données.";
				include("vues/v_erreurs.php");
				include("vues/v_commande.php");
			}
		}
	}

	/**
	 * teste si une chaîne a un format de code postal
	 *
	 * Teste le nombre de caractères de la chaîne et le type entier (composé de chiffres)
	 * 
	 * @param string $cp  la chaîne testée
	 * @return boolean vrai ou faux
	 */
	function estUnCp($cp)
	{
		return strlen($cp) == 5 && ctype_digit($cp);
	}

	/**
	 * Teste si une chaîne a le format d'un mail
	 *
	 * @param string $mail la chaîne testée
	 * @return boolean vrai ou faux
	 */
	function estUnMail($mail)
	{
		return filter_var($mail, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * Retourne un tableau d'erreurs de saisie pour une commande
	 *
	 * @param string $nom  chaîne testée
	 * @param  string $rue chaîne
	 * @param string $ville chaîne
	 * @param string $cp chaîne
	 * @param string $mail  chaîne 
	 * @return array un tableau de chaînes d'erreurs
	 */
	function getErreursSaisieCommande($nom, $rue, $ville, $cp, $mail)
	{
		$lesErreurs = array();
		if ($nom == "")
			$lesErreurs[] = "Il faut saisir le champ nom";
		if ($rue == "")
			$lesErreurs[] = "Il faut saisir le champ rue";
		if ($ville == "")
			$lesErreurs[] = "Il faut saisir le champ ville";
		if (!$this->estUnCp($cp))
			$lesErreurs[] = "Erreur de code postal";
		if (!$this->estUnMail($mail))
			$lesErreurs[] = "Erreur de mail";
		return $lesErreurs;
	}
}