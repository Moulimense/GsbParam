<?php
require_once 'modele/ModeleBack.php';
require_once 'modele/ModeleFront.php';

class ControleurAdmin
{
    private $modeleBack;
    private $modeleFront;

    public function __construct()
    {
        $this->modeleBack = new ModeleBack();
        $this->modeleFront = new ModeleFront();
    }

    public function connexion()
    {
        include("vues/v_connexionAdmin.php");
    }

    public function validerConnexion()
    {
        $login = $_REQUEST['login'];
        $mdp = $_REQUEST['mdp'];

        $admin = $this->modeleBack->verifierAdmin($login, $mdp);

        if ($admin) {
            $_SESSION['admin'] = $admin->nom;
            $this->listeProduits();
        } else {
            $msgErreurs[] = "Identifiants incorrects";
            if (file_exists("vues/v_erreurs.php"))
                include("vues/v_erreurs.php");
            include("vues/v_connexionAdmin.php");
        }
    }

    public function listeProduits()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
        } else {
            $lesProduits = $this->modeleFront->getTousLes_produits();
            include("vues/v_gestionProduits.php");
        }
    }

    public function ajouterProduit()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $desc = $_POST['description'];
            $prix = $_POST['prix'];
            $image = $_POST['image'];

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
            $lesProduits = $this->modeleFront->getTousLes_produits();
            $lesCategories = $this->modeleFront->getLesCategories();
            $action = 'ajouterProduit';
            include("vues/v_gestionProduits.php");
        }
    }

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
            $id = $_REQUEST['produit'];
            $leProduit = $this->modeleFront->getInfosProduit($id);
            $lesProduits = $this->modeleFront->getTousLes_produits();
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
                $lesProduits = $this->modeleFront->getTousLes_produits();
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