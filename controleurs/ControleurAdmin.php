<?php
require_once 'modele/ModeleFront.php'; 

class ControleurAdmin {
    private $modele;

    public function __construct() {
        $this->modele = new ModeleFront();
    }

    // Afficher le formulaire de connexion
    public function connexion() {
        include("vues/v_connexionAdmin.php");
    }

    // Vérifier les identifiants
    public function validerConnexion() {
        $login = $_REQUEST['login'];
        $mdp = $_REQUEST['mdp'];
        
        // On récupère l'admin en base
        $admin = $this->modele->getInfosAdmin($login); 

        // password_verify vérifie le hash sécurisé 
        if ($admin && password_verify($mdp, $admin->mdp)) {
            $_SESSION['admin'] = $admin->nom;
            $this->listeProduits();
        } else {
            $msgErreurs[] = "Identifiants incorrects";
            include("vues/v_erreurs.php");
            include("vues/v_connexionAdmin.php");
        }
    }

    // Liste des produits (Protégée)
    public function listeProduits() {
        if(!isset($_SESSION['admin'])) {
            $this->connexion();
        } else {
            $lesProduits = $this->modele->getLesProduitsDeCategorie();
            include("vues/v_gestionProduits.php");
        }
    }

    public function supprimer() {
        if(isset($_SESSION['admin'])) {
            $id = $_REQUEST['produit'];
            $this->modele->supprimerProduit($id);
            // Redirection vers la liste pour voir le changement
            $this->listeProduits(); 
        } else {
            $this->connexion();
        }
    }

    public function validerCreation() {
        if(isset($_SESSION['admin'])) {
            $id = $_REQUEST['id'];
            $desc = $_REQUEST['description'];
            $prix = $_REQUEST['prix'];
            $cat = $_REQUEST['idCategorie'];
            $img = "images/defaut.jpg"; // On peut simplifier l'image pour débuter
            
            $this->modele->creerProduit($id, $desc, $prix, $img, $cat);
            $this->listeProduits();
        } else {
            $this->connexion();
        }
    }

    public function deconnexion() {
        session_destroy();
        header("Location: index.php");
    }
}
?>
