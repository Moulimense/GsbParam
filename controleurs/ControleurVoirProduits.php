<?php
/**
 * @file ControleurVoirProduits.php
 * @author Marielle Jouin <jouin.marielle@gmail.com>
 * @version    3.0
 * @details contient les fonctions pour voir les produits
 *
 * regroupe les fonctions pour voir les produits
 */
/**
 * @class ControleurVoirProduits
 * @brief contient les fonctions pour gérer l'affichage des produits
 */
class ControleurVoirProduits
{
    private $modeleFront;

    public function __construct()
    {
        $this->modeleFront = new ModeleFront();
    }

    /**
     * Affiche les produits
     *
     * si $categ contient un idCategorie affiche les produits d'une catégorie
     * @param $categ un identifiant de la catégorie de produits à afficher
     */
    public function voirProduits($categ = null)
    {
        $lesCategories = $this->modeleFront->getLesCategories();

        if ($categ == null) {
            // Évolution 2 : Tous les produits
            $lesProduits = $this->modeleFront->getLesProduitsDeCategorie();
            $titre = "Tous nos produits";
        } else {
            // Évolution 1 : Titre dynamique par catégorie
            $lesProduits = $this->modeleFront->getLesProduitsDeCategorie($categ);
            $laCategorie = $this->modeleFront->getLesInfosCategorie($categ);
            $titre = "Produits de la catégorie : " . ($laCategorie ? $laCategorie->libelle : "Inconnue");
        }

        include("vues/v_choixCategorie.php");
        include("vues/v_produits.php");
    }

    /**
     * Affiche le menu à gauche contenant les catégories
     */
    public function voirCategories()
    {
        $lesCategories = $this->modeleFront->getLesCategories();
        include("vues/v_choixCategorie.php");
    }
}
?>