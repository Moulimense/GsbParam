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
        $cp = $_POST['cp'];

        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $msgErreurs[] = "L'adresse email n'est pas valide.";
            include 'vues/v_erreurs.php';
            include 'vues/v_inscription.php';
        } else if ($this->modeleFront->estMailUtilise($mail)) {
            $msgErreurs[] = "Cette adresse e-mail est déjà utilisée.";
            include 'vues/v_erreurs.php';
            include 'vues/v_inscription.php';
        } else if (!preg_match('/^[0-9]{5}$/', $cp)) {
            $msgErreurs[] = "Le code postal doit être composé d'exactement 5 chiffres.";
            include 'vues/v_erreurs.php';
            include 'vues/v_inscription.php';
        } else if ($mdp !== $mdp2) {
            $msgErreurs[] = "Les mots de passe ne correspondent pas.";
            include 'vues/v_erreurs.php';
            include 'vues/v_inscription.php';
        } else if (strlen($mdp) < 12 || !preg_match('/[A-Z]/', $mdp) || !preg_match('/[a-z]/', $mdp) || !preg_match('/[0-9]/', $mdp) || !preg_match('/[\W_]/', $mdp)) {
            $msgErreurs[] = "Le mot de passe doit respecter les règles de sécurité (minimum 12 caractères, avec majuscule, minuscule, chiffre et caractère spécial).";
            include 'vues/v_erreurs.php';
            include 'vues/v_inscription.php';
        } else {
            $ok = $this->modeleFront->inscrireClient($nom, $prenom, $_POST['rue'], $_POST['cp'], $_POST['ville'], $mail, $mdp);
            if ($ok) {
                // Inscription réussie, affichage direct de la vue de connexion
                $this->afficherConnexion();
            } else {
                $msgErreurs[] = "Une erreur est survenue lors de l'inscription.";
                include 'vues/v_erreurs.php';
                include 'vues/v_inscription.php';
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
            $lesAvis = $this->modeleFront->getLesAvisProduit($idProduit);
            $aDejaDonneAvis = false;
            if (isset($_SESSION['idClient'])) {
                $aDejaDonneAvis = $this->modeleFront->aDejaDonneAvis($idProduit, $_SESSION['idClient']);
            }
            include("vues/v_choixCategorie.php");
            include("vues/v_detailsProduit.php");
        } else {
            $this->voirProduits();
        }
    }

    public function ajouterAvis()
    {
        if (!isset($_SESSION['idClient'])) {
            header('Location: index.php?uc=connexion&action=demanderConnexion');
            exit();
        }

        $idProduit = $_POST['idProduit'] ?? null;
        $note = (int)($_POST['note'] ?? 0);
        $commentaire = htmlspecialchars($_POST['commentaire'] ?? '');

        if (!$idProduit) {
            header('Location: index.php');
            exit();
        }

        if ($note < 1 || $note > 5) {
            $msgErreurs[] = "Veuillez attribuer une note entre 1 et 5 étoiles avant de valider votre avis.";
            $lesCategories = $this->modeleFront->getLesCategories();
            $unProduit = $this->modeleFront->getInfosProduit($idProduit);
            $produitsAssocies = $this->modeleFront->getProduitsAssocies($unProduit->idCategorie, $idProduit, 4);
            $lesAvis = $this->modeleFront->getLesAvisProduit($idProduit);
            
            include("vues/v_choixCategorie.php");
            include("vues/v_erreurs.php");
            include("vues/v_detailsProduit.php");
            return;
        }

        if ($this->modeleFront->aDejaDonneAvis($idProduit, $_SESSION['idClient'])) {
            $msgErreurs[] = "Vous avez déjà donné votre avis sur ce produit.";
            $lesCategories = $this->modeleFront->getLesCategories();
            $unProduit = $this->modeleFront->getInfosProduit($idProduit);
            $produitsAssocies = $this->modeleFront->getProduitsAssocies($unProduit->idCategorie, $idProduit, 4);
            $lesAvis = $this->modeleFront->getLesAvisProduit($idProduit);
            
            include("vues/v_choixCategorie.php");
            include("vues/v_erreurs.php");
            include("vues/v_detailsProduit.php");
            return;
        }

        $this->modeleFront->ajouterAvis($idProduit, $_SESSION['idClient'], $note, $commentaire);
        $this->modeleFront->mettreAJourNoteProduit($idProduit);
        
        $message = "Votre avis a bien été enregistré. Merci !";
        
        $lesCategories = $this->modeleFront->getLesCategories();
        $unProduit = $this->modeleFront->getInfosProduit($idProduit);
        $produitsAssocies = $this->modeleFront->getProduitsAssocies($unProduit->idCategorie, $idProduit, 4);
        $lesAvis = $this->modeleFront->getLesAvisProduit($idProduit);
        
        include("vues/v_choixCategorie.php");
        include("vues/v_message.php");
        include("vues/v_detailsProduit.php");
    }

    public function afficherConnexion()
    {
        include 'vues/v_connexion.php';
    }

    public function validerConnexion()
    {
        $mail = $_POST['mail'] ?? '';
        $mdp = $_POST['mdp'] ?? '';

        $client = $this->modeleFront->verifierClient($mail, $mdp);

        if ($client) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['idClient'] = $client->id;
            $_SESSION['nomClient'] = $client->nom;
            $_SESSION['prenomClient'] = $client->prenom;
            
            header('Location: index.php');
            exit();
        } else {
            $msgErreurs[] = "Email ou mot de passe incorrect";
            include 'vues/v_erreurs.php';
            include 'vues/v_connexion.php';
        }
    }

    public function deconnexion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['idClient'], $_SESSION['nomClient'], $_SESSION['prenomClient']);
        header('Location: index.php');
        exit();
    }
}
?>