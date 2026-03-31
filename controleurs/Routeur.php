<?php
require_once 'controleurs/ControleurVoirProduits.php';
require_once 'controleurs/ControleurAccueil.php';
require_once 'controleurs/ControleurGererPanier.php';
/**
 * @class Routeur
 * @brief gère les routes (actions à exécuter en fonction des urls)
 */
class Routeur{
    
    private $ctrlVoirProduits;
    private $ctrlAccueil;
    private $ctrlGererPanier;
    private $ctrlAdmin;
    
    public function __construct(){
        
        $this->ctrlVoirProduits=new ControleurVoirProduits();
        $this->ctrlAccueil=new ControleurAccueil();
        $this->ctrlGererPanier=new ControleurGererPanier();
    }
    /** recupère les paramètres de l'url et active les contrôleurs nécessaires
    */
    public function routerRequete()
    {
    // traitement des paramètres de l'url
    if(isset($_REQUEST['uc']))
    	$uc = $_REQUEST['uc'];
        else $uc='accueil';
    if(isset($_REQUEST['action']))
    	$action = $_REQUEST['action'];
    else $action=null;
    switch($uc)
    {
        case 'accueil':
            include("vues/v_choixRole.php");
            break;
        case 'visiteur':
        case 'voirProduits' :
            switch ($action)
            {
                case null :
                case 'voirCategories' : {$this->ctrlVoirProduits->voirCategories();break;}
                case 'voirProduits' : {$this->ctrlVoirProduits->voirProduits($_REQUEST['categorie']);break;}
                case 'nosProduits' : {$this->ctrlVoirProduits->voirProduits();break;}
            }; break;
        case 'gererPanier' :
            switch ($action)
            {
                case null :
                case 'voirPanier' : {$this->ctrlGererPanier->voirPanier();break;}
                case 'ajouterAuPanier' : {$this->ctrlGererPanier->ajouterAuPanier($_REQUEST['produit']);break;}
                case 'modifier' : {$this->ctrlGererPanier->modifierQuantite($_REQUEST['produit'], $_REQUEST['qte']);break;}
                case 'retirerDuPanier' : {$this->ctrlGererPanier->retirerDuPanier($_REQUEST['produit']);break;}
                case 'viderPanier' : {$this->ctrlGererPanier->viderPanier();break;}
                case 'passerCommande' : $this->ctrlGererPanier->passerCommande();break;
                case 'confirmerCommande' : $this->ctrlGererPanier->confirmerCommande();break;
                default: {$this->ctrlGererPanier->voirPanier();break;}             
            }; break;
             
        case 'administrer' :
            require_once 'controleurs/ControleurAdmin.php';
            $this->ctrlAdmin = new ControleurAdmin();
            switch ($action) {
                case 'connexion' : $this->ctrlAdmin->login(); break;
                case 'validerConnexion' : $this->ctrlAdmin->validerConnexion(); break;
                case 'listeProduits' : $this->ctrlAdmin->gestionProduits(); break;
                // Ajouter ici les cases pour ajouter, modifier, supprimer
                default : $this->ctrlAdmin->login();
            }
            break; 
    }
    }
}
