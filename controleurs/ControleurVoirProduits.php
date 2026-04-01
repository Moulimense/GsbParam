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
        $this->modeleFront = new
            ModeleFront();
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

        if ($categ == null || $categ == 'tous') {
            // Évolution 2 : Tous les produits
            $lesProduits = $this->modeleFront->getTousLesProduits();
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
    public function afficherInscription()
    {
        include 'vues/v_inscription.php';
    }

    public function enregistrerInscription()
    {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mail = $_POST['mail'];
        $mdp = $_POST['mdp'];
        $mdp2 = $_POST['mdp2'];
        // récupère les autres champs si nécessaire...

        if ($mdp != $mdp2) {
            $msgErreurs[] = "Les mots de passe ne correspondent pas";
            include 'vues/v_erreurs.php';
            include 'vues/v_inscription.php';
        } else {
            $modele = new ModeleFront();
            $ok = $modele->inscrireClient($nom, $prenom, $_POST['rue'], $_POST['cp'], $_POST['ville'], $mail, $mdp);
            if ($ok) {
                echo "Inscription réussie !";
                // Tu peux inclure la vue de connexion ici
            }
        }
    }

    /**
     * Affiche les détails d'un produit et les produits recommandés
     */
    public function voirDetailsProduit($idProduit)
    {
        $lesCategories = $this->modeleFront->getLesCategories();
        $unProduit = $this->modeleFront->getInfosProduit($idProduit);

        if ($unProduit) {
            $produitsAssocies = $this->modeleFront->getProduitsAssocies($unProduit->idCategorie, $idProduit, 4);
            include("vues/v_choixCategorie.php");
            include("vues/v_detailsProduit.php");
        } else {
            $this->voirProduits();
        }
    }
}
?>