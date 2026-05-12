<?php
/** @file ControleurAccueil.php
 * @author Marielle Jouin <jouin.marielle@gmail.com>
 * @version    3.0
 * @details Gère l'affichage de la page d'accueil du site
*/
require_once 'Modele/ModeleFront.php';
/**
 * @class ControleurAccueil
 * @brief contient la fonction qui gère l'accueil
 */
class ControleurAccueil{
    private $produit;

    public function __construct()
    {
        $this->produit=new ModeleFront();
    }
    /**
	 * affiche la page d'accueil
	*/
    public function accueil(){
        // Nettoyage quotidien (simulé à chaque chargement de l'accueil)
        $this->produit->nettoyerPromotionsExpirees();
        
        // Récupération des produits mis en avant
        $produitsEnPromotion = $this->produit->getProduitsEnPromotion();
        
        include("vues/v_accueil.php");
    }
}
