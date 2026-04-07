<?php
require_once 'controleurs/ControleurVoirProduits.php';
require_once 'controleurs/ControleurAccueil.php';
require_once 'controleurs/ControleurGererPanier.php';


/**
 * @class Routeur
 * @brief gère les routes (actions à exécuter en fonction des urls)
 */
class Routeur
{

    private $ctrlVoirProduits;
    private $ctrlAccueil;
    private $ctrlGererPanier;
    private $ctrlAdmin;

    public function __construct()
    {
        $this->ctrlVoirProduits = new ControleurVoirProduits();
        $this->ctrlAccueil = new ControleurAccueil();
        $this->ctrlGererPanier = new ControleurGererPanier();
    }

    public function routerRequete()
    {
        if (isset($_REQUEST['uc']))
            $uc = $_REQUEST['uc'];
        else
            $uc = 'voirProduits';

        if (isset($_REQUEST['action']))
            $action = $_REQUEST['action'];
        else
            $action = 'nosProduits';

        switch ($uc) {
            case 'accueil':
                $this->ctrlVoirProduits->voirProduits();
                break;

            case 'connexion':
                switch ($action) {
                    case 'demanderInscription':
                        $this->ctrlVoirProduits->afficherInscription();
                        break;
                    case 'confirmerInscription':
                        $this->ctrlVoirProduits->enregistrerInscription();
                        break;
                    case 'demanderConnexion':
                        $this->ctrlVoirProduits->afficherConnexion();
                        break;
                    case 'validerConnexion':
                        $this->ctrlVoirProduits->validerConnexion();
                        break;
                    case 'deconnexion':
                        $this->ctrlVoirProduits->deconnexion();
                        break;
                }
                break;

            case 'visiteur':
            case 'voirProduits':
                switch ($action) {
                    case null:
                    case 'voirCategories':
                        $this->ctrlVoirProduits->voirCategories();
                        break;
                    case 'voirProduits':
                        $this->ctrlVoirProduits->voirProduits($_REQUEST['categorie']);
                        break;
                    case 'nosProduits':
                        $this->ctrlVoirProduits->voirProduits();
                        break;
                    case 'voirDetails':
                        $this->ctrlVoirProduits->voirDetailsProduit($_REQUEST['produit']);
                        break;
                }
                break;

            case 'gererPanier':
                switch ($action) {
                    case 'voirPanier':
                        $this->ctrlGererPanier->voirPanier();
                        break;
                    case 'ajouterAuPanier':
                        $this->ctrlGererPanier->ajouterAuPanier($_REQUEST['produit']);
                        break;
                    case 'modifier':
                        $this->ctrlGererPanier->modifierQuantite($_REQUEST['produit'], $_REQUEST['qte']);
                        break;
                    case 'retirerDuPanier':
                        $this->ctrlGererPanier->retirerDuPanier($_REQUEST['produit']);
                        break;
                    case 'viderPanier':
                        $this->ctrlGererPanier->viderPanier();
                        break;
                    case 'passerCommande':
                        $this->ctrlGererPanier->passerCommande();
                        break;
                    case 'confirmerCommande':
                        $this->ctrlGererPanier->confirmerCommande();
                        break;
                    default:
                        $this->ctrlGererPanier->voirPanier();
                        break;
                }
                break;

            case 'administrer':
                require_once 'controleurs/ControleurAdmin.php';
                $this->ctrlAdmin = new ControleurAdmin();
                switch ($action) {
                    case 'connexion':
                        $this->ctrlAdmin->login();
                        break;
                    case 'validerConnexion':
                        $this->ctrlAdmin->validerConnexion();
                        break;
                    case 'listeProduits':
                        $this->ctrlAdmin->gestionProduits();
                        break;
                    default:
                        $this->ctrlAdmin->login();
                }
                break;
        }
    }
}

