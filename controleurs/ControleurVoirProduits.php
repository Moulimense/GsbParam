<?php
/**
 * @file ControleurVoirProduits.php
 * @author Marielle Jouin <jouin.marielle@gmail.com>
 * @version    3.0
 * @details contient les fonctions pour voir les produits

 * regroupe les fonctions pour voir les produits
 */
/**
 * @class ControleurVoirProduits
 * @brief contient les fonctions pour gérer l'affichage des produits
 */
class ControleurVoirProduits{
    private $modeleFront;

    public function __construct()
    {
        $this->modeleFront=new ModeleFront();
    }
	/**
	 * Affiche les produits
	 *
	 * si $categ contient un idCategorie affiche les produits d'une catégorie
	 * @param $categ un identifiant de la catégorie de produits à afficher
	*/
    public function voirProduits($categ){
		$lesProduits=$this->modeleFront->getLesProduitsDeCategorie($categ);
        $lesCategories=$this->modeleFront->getLesCategories();
        
        include("vues/v_choixCategorie.php");
        include("vues/v_produits.php");
    }
	/**
	 * Affiche le menu à gauche contenant les catégories
	*/
    public function voirCategories(){
		$lesCategories=$this->modeleFront->getLesCategories();
        include("vues/v_choixCategorie.php");
	}
}

?>

