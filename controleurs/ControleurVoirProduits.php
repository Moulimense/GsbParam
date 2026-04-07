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
        // Récupération des données du formulaire (POST ou GET via $_REQUEST)
        $categ = isset($_REQUEST['categorie']) ? $_REQUEST['categorie'] : $categ;
        $prixMin = !empty($_REQUEST['prixMin']) ? $_REQUEST['prixMin'] : null;
        $prixMax = !empty($_REQUEST['prixMax']) ? $_REQUEST['prixMax'] : null;
        $marque = !empty($_REQUEST['marque']) ? $_REQUEST['marque'] : null;

        // Préparation des données pour la vue
        $lesCategories = $this->modeleFront->getLesCategories();
        $lesMarques = $this->modeleFront->getLesMarques();
        $lesProduits = $this->modeleFront->getLesProduitsFiltres($categ, $prixMin, $prixMax, $marque);

        // Détermination du titre de la page
        if ($categ && $categ != 'tous') {
            $laCategorie = $this->modeleFront->getLesInfosCategorie($categ);
            $titre = "Catégorie : " . ($laCategorie ? $laCategorie->libelle : "Inconnue");
        } else {
            $titre = "Tous nos produits";
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