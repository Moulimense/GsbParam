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
            header("Location: index.php?uc=administrer&action=listeProduits");
            exit();
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
            $lesProduits = $this->modeleFront->getTousLesProduits();
            $messageSucces = null;
            if (isset($_SESSION['message_succes'])) {
                $messageSucces = $_SESSION['message_succes'];
                unset($_SESSION['message_succes']);
            }
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

            $cat = $_POST['idCategorie'];
            $stock = isset($_POST['stock']) ? max(0, (int)$_POST['stock']) : 0;

            $this->modeleBack->ajouterProduit($nom, $desc, $prix, $image, $cat, $stock);
            $_SESSION['message_succes'] = "Le produit a été ajouté avec succès.";
            $this->listeProduits();
        } else {
            $lesProduits = $this->modeleFront->getTousLesProduits();
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

            $cat = $_POST['idCategorie'];
            $stock = isset($_POST['stock']) ? max(0, (int)$_POST['stock']) : 0;

            $this->modeleBack->modifierProduit($id, $nom, $desc, $prix, $image, $cat, $stock);
            $_SESSION['message_succes'] = "Le produit a été modifié avec succès.";
            $this->listeProduits();
        } else {
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

    public function ajouterNouvelleCategorie()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = trim($_POST['nom'] ?? '');
            
            if (empty($libelle)) {
                $erreur = "Veuillez saisir un nom pour la nouvelle catégorie.";
                include("vues/v_ajouterCategorie.php");
            } else {
                $this->modeleBack->creerNouvelleCategorie($libelle);
                $_SESSION['message_succes'] = "La catégorie '$libelle' a été créée avec succès.";
                header("Location: index.php?uc=voirProduits&action=nosProduits");
                exit();
            }
        } else {
            include("vues/v_ajouterCategorie.php");
        }
    }

    public function supprimerCategories()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoriesSelectionnees = $_POST['categories'] ?? [];

            if (empty($categoriesSelectionnees)) {
                $erreur = "Veuillez sélectionner au moins une catégorie à supprimer.";
                $lesCategories = $this->modeleFront->getLesCategories();
                include("vues/v_supprimerCategories.php");
                return;
            }

            $categoriesNonVides = [];
            foreach ($categoriesSelectionnees as $idCat) {
                if (!$this->modeleBack->estCategorieVide($idCat)) {
                    $categoriesNonVides[] = $idCat;
                }
            }

            if (!empty($categoriesNonVides)) {
                $erreur = "Impossible de supprimer : la ou les catégorie(s) suivante(s) contiennent encore des produits : " . implode(', ', $categoriesNonVides) . ".";
                $lesCategories = $this->modeleFront->getLesCategories();
                include("vues/v_supprimerCategories.php");
                return;
            }

            $nbSupprimees = count($categoriesSelectionnees);
            foreach ($categoriesSelectionnees as $idCat) {
                $this->modeleBack->supprimerCategorie($idCat);
            }

            $_SESSION['message_succes'] = "$nbSupprimees catégorie(s) supprimée(s) avec succès.";
            header("Location: index.php?uc=voirProduits&action=nosProduits");
            exit();
        } else {
            $lesCategories = $this->modeleFront->getLesCategories();
            include("vues/v_supprimerCategories.php");
        }
    }

    public function modifierCategorie()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['validerSelection'])) {
                $idCat = $_POST['idCategorie'] ?? '';
                if (empty($idCat)) {
                    $erreur = "Veuillez sélectionner la catégorie que vous souhaitez éditer.";
                    $lesCategories = $this->modeleFront->getLesCategories();
                    include("vues/v_modifierCategorie.php");
                } else {
                    $laCategorieSelected = $this->modeleFront->getLesInfosCategorie($idCat);
                    include("vues/v_modifierCategorie.php");
                }
            } elseif (isset($_POST['validerModification'])) {
                $idCat = $_POST['idCategorie'] ?? '';
                $nouveauNom = trim($_POST['nouveauNom'] ?? '');
                
                if (empty($nouveauNom)) {
                    $erreur = "Veuillez saisir un nouveau nom pour la catégorie.";
                    $laCategorieSelected = $this->modeleFront->getLesInfosCategorie($idCat);
                    include("vues/v_modifierCategorie.php");
                } else {
                    $this->modeleBack->modifierCategorie($idCat, $nouveauNom);
                    $_SESSION['message_succes'] = "La catégorie a été renommée en '$nouveauNom' avec succès.";
                    header("Location: index.php?uc=voirProduits&action=nosProduits");
                    exit();
                }
            }
        } else {
            $lesCategories = $this->modeleFront->getLesCategories();
            include("vues/v_modifierCategorie.php");
        }
    }

    public function gestionStock()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stocks = $_POST['stocks'] ?? [];
            $erreurs = [];

            foreach ($stocks as $idProduit => $valeur) {
                $valeur = trim($valeur);

                if (!is_numeric($valeur)) {
                    $erreurs[] = "Valeur invalide pour le produit '$idProduit' : la saisie doit être un nombre.";
                    continue;
                }

                if ((int)$valeur < 0) {
                    $erreurs[] = "Valeur invalide pour le produit '$idProduit' : le stock ne peut pas être négatif.";
                    continue;
                }
            }

            if (!empty($erreurs)) {
                $erreur = implode(" | ", $erreurs);
                $lesProduits = $this->modeleFront->getTousLesProduitsAvecStock();
                include("vues/v_gestionStock.php");
                return;
            }

            $lesProduitsActuels = $this->modeleFront->getTousLesProduitsAvecStock();
            $stocksActuels = [];
            foreach ($lesProduitsActuels as $p) {
                $stocksActuels[$p->id] = (int)$p->stock;
            }

            $nbMaj = 0;
            foreach ($stocks as $idProduit => $valeur) {
                $nouvelleValeur = (int)$valeur;
                if (isset($stocksActuels[$idProduit]) && $stocksActuels[$idProduit] !== $nouvelleValeur) {
                    $this->modeleBack->mettreAJourStock($idProduit, $nouvelleValeur);
                    $nbMaj++;
                }
            }

            if ($nbMaj > 0) {
                $phrase = ($nbMaj == 1) ? "Le stock d'un produit a été mis à jour" : "Les stocks de $nbMaj produits ont été mis à jour";
                $_SESSION['message_succes'] = "$phrase avec succès.";
            } else {
                $_SESSION['message_succes'] = "Aucune modification n'a été effectuée.";
            }
            header("Location: index.php?uc=administrer&action=gestionStock");
            exit();

        } else {
            $messageSucces = null;
            if (isset($_SESSION['message_succes'])) {
                $messageSucces = $_SESSION['message_succes'];
                unset($_SESSION['message_succes']);
            }

            if (isset($_REQUEST['filtreCritique']) && $_REQUEST['filtreCritique'] == '1') {
                $lesProduits = $this->modeleFront->getProduitsStockCritique(5);
            } else {
                $lesProduits = $this->modeleFront->getTousLesProduitsAvecStock();
            }

            include("vues/v_gestionStock.php");
        }
    }

    public function gestionAssociations()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        $messageSucces = null;
        if (isset($_SESSION['message_succes'])) {
            $messageSucces = $_SESSION['message_succes'];
            unset($_SESSION['message_succes']);
        }

        $erreur = null;
        if (isset($_SESSION['erreur'])) {
            $erreur = $_SESSION['erreur'];
            unset($_SESSION['erreur']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $actionP = $_POST['actionP'] ?? '';

            if ($actionP === 'creer') {
                $id1 = $_POST['idProduit'] ?? '';
                $id2 = $_POST['idProduitAssocie'] ?? '';

                if (empty($id1) || empty($id2)) {
                    $_SESSION['erreur'] = "Veuillez sélectionner deux produits.";
                } elseif ($id1 === $id2) {
                    $_SESSION['erreur'] = "Un produit ne peut pas être associé à lui-même.";
                } elseif ($this->modeleBack->associationExiste($id1, $id2)) {
                    $_SESSION['erreur'] = "L'ajout a échoué : cette association existe déjà.";
                } else {
                    $this->modeleBack->ajouterAssociation($id1, $id2);
                    $_SESSION['message_succes'] = "Association créée avec succès.";
                }
            } elseif ($actionP === 'supprimer') {
                $id1 = $_POST['idProduit'] ?? '';
                $id2 = $_POST['idProduitAssocie'] ?? '';
                $this->modeleBack->supprimerAssociation($id1, $id2);
                $_SESSION['message_succes'] = "Association supprimée avec succès.";
            } elseif ($actionP === 'modifier') {
                $ancienId1 = $_POST['ancienIdProduit'] ?? '';
                $ancienId2 = $_POST['ancienIdProduitAssocie'] ?? '';
                $nouveauId1 = $_POST['idProduit'] ?? '';
                $nouveauId2 = $_POST['idProduitAssocie'] ?? '';

                if (empty($nouveauId1) || empty($nouveauId2)) {
                    $_SESSION['erreur'] = "Veuillez sélectionner deux produits.";
                } elseif ($nouveauId1 === $nouveauId2) {
                    $_SESSION['erreur'] = "Un produit ne peut pas être associé à lui-même.";
                } elseif (($ancienId1 !== $nouveauId1 || $ancienId2 !== $nouveauId2) && $this->modeleBack->associationExiste($nouveauId1, $nouveauId2)) {
                    $_SESSION['erreur'] = "La modification a échoué : cette association existe déjà.";
                } else {
                    $this->modeleBack->modifierAssociation($ancienId1, $ancienId2, $nouveauId1, $nouveauId2);
                    $_SESSION['message_succes'] = "Association modifiée avec succès.";
                }
            }
            header("Location: index.php?uc=administrer&action=gestionAssociations");
            exit();
        } else {
            $lesAssociations = $this->modeleBack->getLesAssociations();
            $lesProduits = $this->modeleFront->getTousLesProduits();
            include("vues/v_gestionAssociations.php");
        }
    }

    /**
     * Gestion des commandes
     */
    public function gestionCommandes()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        $messageSucces = null;
        if (isset($_SESSION['message_succes'])) {
            $messageSucces = $_SESSION['message_succes'];
            unset($_SESSION['message_succes']);
        }

        $lesCommandes = $this->modeleBack->getLesCommandes();
        include("vues/v_gestionCommandes.php");
    }

    /**
     * Retourne les articles d'une commande en JSON (pour la modale AJAX)
     */
    public function articlesCommande()
    {
        if (!isset($_SESSION['admin'])) {
            header('HTTP/1.1 403 Forbidden');
            exit();
        }

        $idCommande = $_REQUEST['idCommande'] ?? '';
        $articles = $this->modeleBack->getArticlesCommande($idCommande);

        $total = 0;
        $data = [];
        foreach ($articles as $art) {
            $sousTotal = $art->prix * $art->quantite;
            $total += $sousTotal;
            $data[] = [
                'description' => $art->description,
                'prix' => number_format($art->prix, 2, '.', ''),
                'marque' => $art->marque ?: 'Non spécifiée',
                'categorie' => $art->idCategorie,
                'quantite' => $art->quantite,
                'sousTotal' => number_format($sousTotal, 2, '.', '')
            ];
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/json');
        echo json_encode(['articles' => $data, 'total' => number_format($total, 2, '.', '')]);
        exit();
    }

    /**
     * Modifie l'état d'une commande
     */
    public function modifierEtatCommande()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        $idCommande = $_POST['idCommande'] ?? '';
        $etat = $_POST['etat'] ?? '';

        if (!empty($idCommande) && !empty($etat)) {
            $this->modeleBack->modifierEtatCommande($idCommande, $etat);
            $_SESSION['message_succes'] = "L'état de la commande #$idCommande a été modifié en \"$etat\" avec succès.";
        }

        header("Location: index.php?uc=administrer&action=gestionCommandes");
        exit();
    }

    /**
     * Gère l'affichage et la suppression des promotions
     */
    public function gestionPromotions()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        $messageSucces = null;
        if (isset($_SESSION['message_succes'])) {
            $messageSucces = $_SESSION['message_succes'];
            unset($_SESSION['message_succes']);
        }

        $this->modeleBack->nettoyerPromotionsExpirees();
        $lesPromotions = $this->modeleBack->getLesPromotions();
        $lesProduits = $this->modeleFront->getTousLesProduits();
        include("vues/v_gestionPromotions.php");
    }

    /**
     * Traite l'ajout d'une nouvelle promotion
     */
    public function ajouterPromotion()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        $idProduit = $_POST['produit'] ?? null;
        $dateDebut = $_POST['dateDebut'] ?? null;
        $dateFin = $_POST['dateFin'] ?? null;

        if (!$idProduit || !$dateDebut || !$dateFin) {
            $msgErreurs[] = "Tous les champs sont obligatoires.";
        } else {
            $aujourdHui = date('Y-m-d');
            if ($dateDebut < $aujourdHui) {
                $msgErreurs[] = "La date de début ne peut pas être inférieure à la date du jour.";
            } elseif ($dateFin < $dateDebut) {
                $msgErreurs[] = "La date de fin ne peut pas être inférieure à la date de début.";
            } elseif ($this->modeleBack->promotionExiste($idProduit, $dateDebut, $dateFin)) {
                $msgErreurs[] = "Une promotion existe déjà pour ce produit pendant cette période (chevauchement de dates).";
            } else {
                $this->modeleBack->ajouterPromotion($idProduit, $dateDebut, $dateFin);
                $_SESSION['message_succes'] = "La programmation a été ajoutée avec succès.";
            }
        }

        if (!empty($msgErreurs)) {
            $lesPromotions = $this->modeleBack->getLesPromotions();
            $lesProduits = $this->modeleFront->getTousLesProduits();
            include("vues/v_gestionPromotions.php"); // Will include v_erreurs.php inside
        } else {
            header('Location: index.php?uc=administrer&action=gestionPromotions');
            exit();
        }
    }

    /**
     * Supprime une promotion
     */
    public function supprimerPromotion()
    {
        if (!isset($_SESSION['admin'])) {
            $this->connexion();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idPromotion = $_POST['promotion'] ?? null;
            if ($idPromotion) {
                $this->modeleBack->supprimerPromotion($idPromotion);
                $_SESSION['message_succes'] = "La programmation a été supprimée avec succès.";
            }
        }
        header('Location: index.php?uc=administrer&action=gestionPromotions');
        exit();
    }
}