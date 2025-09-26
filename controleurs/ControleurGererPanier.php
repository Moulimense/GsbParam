<?php
/**
 * Mission GsbParam PHP Objet
 * 
 * @file ControleurGererPanier.php
 * @author Marielle Jouin <jouin.marielle@gmail.com>
 * @version    3.0
 * @brief contient les fonctions pour gérer le panier

 * regroupe les fonctions pour gérer le panier, et les erreurs de saisie dans le formulaire de commande
*/
/**
 * @class ControleurGererPanier
 * @brief contient les fonctions pour gérer le panier
 */
class ControleurGererPanier{
	private $modeleFront;
	
	public function __construct()
    {
        $this->modeleFront=new ModeleFront();
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
		if(!isset($_SESSION['produits']))
		{
			$_SESSION['produits']= array();
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
			$n=$this->nbProduitsDuPanier();
			if($n >0)
			{
				$desIdProduit = $this->getLesIdProduitsDuPanier();
				$lesProduitsDuPanier = $this->modeleFront->getLesProduitsDuTableau($desIdProduit);
				include("vues/v_panier.php");
			}
			else
			{
				$message = "Le panier est vide !";
				include ("vues/v_message.php");
			}
		}

	/**
	 * Vide le panier
	 *
	 * Supprime le tableau $_SESSION['produits']
	 */
	function viderPanier()
	{
		unset($_SESSION['produits']);
	}
	/**
	 * Ajoute un produit au panier
	 *
	 * Teste si le produit est déjà dans la variable session 
	 * ajoute le produit à la variable de session dans le cas où
	 * où le produit n'a pas été trouvé
	 
	* @param Produit $idProduit Le produit à ajouter au panier 
	*/
	function ajouterAuPanier($idProduit)
	{
		if(in_array($idProduit,$_SESSION['produits']))
		{
			$msgErreurs[]='Ce produit est déjà dans le panier.';
			include("vues/v_erreurs.php");
		}
		else
		{
			$_SESSION['produits'][]= $idProduit; // l'indice n'est pas précisé : il sera automatiquement mis à la fin
		}
		$this->voirPanier();
	}
		
	/**
	 * Retourne les produits du panier
	 *
	 * Retourne le tableau des identifiants de modeleFront
	 
	* @return array $_SESSION['produits'] le tableau des id produits du panier 
	*/
	function getLesIdProduitsDuPanier()
	{
		return $_SESSION['produits'];

	}
	/**
	 * Retourne le nombre de produits du panier
	 *
	 * Teste si la variable de session existe
	 * et retourne le nombre d'éléments de la variable session
	 
	* @return int $n
	*/
	function nbProduitsDuPanier()
	{
		$n = 0;
		if(isset($_SESSION['produits']))
		{
		$n = count($_SESSION['produits']);
		}
		return $n;
	}
	/**
	 * Affiche le formulaire de commande
	*/
	function passerCommande()
	{
		$n=$this->nbProduitsDuPanier();
			if($n>0)
			{   // les variables suivantes servent à l'affectation des attributs value du formulaire
				// ici le formulaire doit être vide, quand il est erroné, le formulaire sera réaffiché pré-rempli
				$nom ='';$rue='';$ville ='';$cp='';$mail='';
				include ("vues/v_commande.php");
			}
			else
			{
				$message = "Votre panier est vide !";
				include ("vues/v_message.php");
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
			$nom =$_REQUEST['nom'];$rue=$_REQUEST['rue'];$ville =$_REQUEST['ville'];$cp=$_REQUEST['cp'];$mail=$_REQUEST['mail'];
			$msgErreurs = $this->getErreursSaisieCommande($nom,$rue,$ville,$cp,$mail);
			if (count($msgErreurs)!=0)
			{
				include ("vues/v_erreurs.php");
				include ("vues/v_commande.php");
			}
			else
			{
				$lesIdProduits = $this->getLesIdProduitsDuPanier();
				$this->modeleFront->creerCommande($nom,$rue,$cp,$ville,$mail, $lesIdProduits );
				$message = "La commande a été enregistrée. Merci de votre visite.";
				$this->supprimerPanier();
				include ("vues/v_message.php");
			}
		}
	/**
	 * Supprime le panier
	 *
	 * Supprime le tableau $_SESSION['produits']
	 */
	function supprimerPanier()
	{
		unset($_SESSION['produits']);
	}
	/**
	 * teste si une chaîne a un format de code postal
	 *
	 * Teste le nombre de caractères de la chaîne et le type entier (composé de chiffres)
	 
	* @param string $codePostal  la chaîne testée
	* @return boolean $ok vrai ou faux
	*/
	function estUnCp($codePostal)
	{
	return strlen($codePostal)== 5 && $this->estEntier($codePostal);
	}
	/**
	 * teste si une chaîne est un entier
	 *
	 * Teste si la chaîne ne contient que des chiffres
	 
	* @param string $valeur la chaîne testée
	* @return boolean $ok vrai ou faux
	*/

	function estEntier($valeur) 
	{
		return preg_match("/[^0-9]/", $valeur) == 0;
	}
	/**
	 * Teste si une chaîne a le format d'un mail
	 *
	 * Utilise les expressions régulières
	 
	* @param string $mail la chaîne testée
	* @return boolean $ok vrai ou faux
	*/
	function estUnMail($mail)
	{
	return  preg_match ('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', $mail);
	}
	/**
	 * Retourne un tableau d'erreurs de saisie pour une commande
	 *
	 * @param string $nom  chaîne testée
	 * @param  string $rue chaîne
	 * @param string $ville chaîne
	 * @param string $cp chaîne
	 * @param string $mail  chaîne 
	 * @return array $lesErreurs un tableau de chaînes d'erreurs
	*/
	function getErreursSaisieCommande($nom,$rue,$ville,$cp,$mail)
	{
		$lesErreurs = array();
		if($nom=="")
		{
			$lesErreurs[]="Il faut saisir le champ nom";
		}
		if($rue=="")
		{
		$lesErreurs[]="Il faut saisir le champ rue";
		}
		if($ville=="")
		{
			$lesErreurs[]="Il faut saisir le champ ville";
		}
		if($cp=="")
		{
			$lesErreurs[]="Il faut saisir le champ code postal";
		}
		else
		{
			if(!$this->estUnCp($cp))
			{
				$lesErreurs[]= "erreur de code postal";
			}
		}
		if($mail=="")
		{
			$lesErreurs[]="Il faut saisir le champ mail";
		}
		else
		{
			if(!$this->estUnMail($mail))
			{
				$lesErreurs[]= "erreur de mail";
			}
		}
		return $lesErreurs;
	}
}