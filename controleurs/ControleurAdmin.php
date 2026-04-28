<?php
require_once 'modele/ModeleBack.php';
require_once 'modele/ModeleFront.php'; // On garde ModeleFront pour getLesCategories() ou autres méthodes si nécessaires

class ControleurAdmin
{
    private $modeleBack;
    private $modeleFront;

    public function __construct()
    {
        $this->modeleBack = new ModeleBack();
        $this->modeleFront = new ModeleFront();
    }

    // Afficher le formulaire de connexion
    public function connexion()
    {
        include("vues/v_connexionAdmin.php");
    }

    // Vérifier les identifiants
    public function validerConnexion()
    {
        $login = $_REQUEST['login'];
        $mdp = $_REQUEST['mdp'];

        $admin = $this->modeleBack->verifierAdmin($login, $mdp);

        if ($admin) {
            // On utilise les crochets [] car c'est souvent un tableau
            // Et on vérifie quelle est la clé exacte dans ta table (peut-être 'nom' ou 'id')
            $_SESSION['admin'] = $admin->nom;
            $this->listeProduits();
        } else {
            $msgErreurs[] = "Identifiants incorrects";
            // Vérifie que ces fichiers existent bien, sinon ça fera une erreur
            if (file_exists("vues/v_erreurs.php"))
                include("vues/v_erreurs.php");
            include("vues/v_connexionAdmin.php");
        }
    }

    // Liste des produits (Protégée)
    public function listeProduits()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
        } else {
            $lesProduits = $this->modeleFront->getTousLesProduits();
            include("vues/v_gestionProduits.php");
        }
    }

    // Action : ajouterProduit
    public function ajouterProduit()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom']; // identifiant
            $desc = $_POST['description'];
            $prix = $_POST['prix'];
            $image = $_POST['image'];
            
            // Création de la catégorie si renseignée
            if (!empty($_POST['nouvelleCatId']) && !empty($_POST['nouvelleCatLibelle'])) {
                $cat = strtoupper($_POST['nouvelleCatId']);
                $libelleCat = $_POST['nouvelleCatLibelle'];
                $this->modeleBack->ajouterCategorie($cat, $libelleCat);
            } else {
                $cat = $_POST['idCategorie'];
            }

            $this->modeleBack->ajouterProduit($nom, $desc, $prix, $image, $cat);
            $this->listeProduits();
        } else {
            $lesProduits = $this->modeleFront->getTousLesProduits();
            $lesCategories = $this->modeleFront->getLesCategories();
            $action = 'ajouterProduit';
            include("vues/v_gestionProduits.php");
        }
    }

    // Action : modifierProduit
    public function modifierProduit()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nom = $_POST['nom'];
            $desc = $_POST['description'];
            $prix = $_POST['prix'];
            $image = $_POST['image'];
            
            // Création de la catégorie si renseignée
            if (!empty($_POST['nouvelleCatId']) && !empty($_POST['nouvelleCatLibelle'])) {
                $cat = strtoupper($_POST['nouvelleCatId']);
                $libelleCat = $_POST['nouvelleCatLibelle'];
                $this->modeleBack->ajouterCategorie($cat, $libelleCat);
            } else {
                $cat = $_POST['idCategorie'];
            }

            $this->modeleBack->modifierProduit($id, $nom, $desc, $prix, $image, $cat);
            $this->listeProduits();
        } else {
            // Affichage du formulaire avec données chargées
            $id = $_REQUEST['produit'];
            $leProduit = $this->modeleFront->getInfosProduit($id);
            $lesProduits = $this->modeleFront->getTousLesProduits();
            $lesCategories = $this->modeleFront->getLesCategories();
            $action = 'modifierProduit';
            include("vues/v_gestionProduits.php");
        }
    }

    public function supprimer()
    {
        if (isset($_SESSION['admin'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['produit'];
                $this->modeleBack->supprimerProduit($id);
                $this->listeProduits();
            } else {
                $id = $_REQUEST['produit'];
                $leProduit = $this->modeleFront->getInfosProduit($id);
                $lesProduits = $this->modeleFront->getTousLesProduits();
                $action = 'supprimer';
                include("vues/v_gestionProduits.php");
            }
        } else {
            $this->connexion();
        }
    }

    public function deconnexion()
    {
        session_destroy();
        header("Location: index.php");
    }
}
?>