<?php
require_once 'modele/Modele.php';

class ModeleBack extends Modele {

    /**
     * Crée un administrateur en hachant son mot de passe
     */
    public function creerAdmin($id, $nom, $mdp) {
        $hash = password_hash($mdp, PASSWORD_DEFAULT);
        try {
            $req = "INSERT INTO administrateur (id, nom, mdp) VALUES (?, ?, ?)";
            $this->executerRequete($req, array($id, $nom, $hash));
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Vérifie les identifiants d'un administrateur
     */
    public function verifierAdmin($nom, $mdp) {
        try {
            $req = "SELECT id, nom, mdp FROM administrateur WHERE nom = ?";
            $res = $this->executerRequete($req, array($nom));
            $admin = $res->fetch(PDO::FETCH_OBJ);

            if ($admin && password_verify($mdp, $admin->mdp)) {
                return $admin;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Ajoute un nouveau produit dans la base
     */
    public function ajouterProduit($nom, $description, $prix, $image, $idCategorie, $stock = 0) {
        $req = "INSERT INTO produit (id, description, prix, image, idCategorie, stock) VALUES (?, ?, ?, ?, ?, ?)";
        $this->executerRequete($req, array($nom, $description, $prix, $image, $idCategorie, (int)$stock));
    }

    /**
     * Modifie un produit existant
     */
    public function modifierProduit($id, $nom, $description, $prix, $image, $idCategorie, $stock = 0) {
        $req = "UPDATE produit SET description = ?, prix = ?, image = ?, idCategorie = ?, stock = ? WHERE id = ?";
        $this->executerRequete($req, array($description, $prix, $image, $idCategorie, (int)$stock, $id));
    }

    /**
     * Supprime un produit de la base
     */
    public function supprimerProduit($id) {
        $req = "delete from produit where id = ?";
        $this->executerRequete($req, array($id));
    }

    /**
     * Crée une nouvelle catégorie si elle n'existe pas déjà
     */
    public function ajouterCategorie($id, $libelle) {
        try {
            $req = "INSERT INTO categorie (id, libelle) VALUES (?, ?)";
            $this->executerRequete($req, array($id, $libelle));
        } catch (PDOException $e) {
            // Si la catégorie existe déjà, on ignore l'erreur
        }
    }

    /**
     * Génère un ID unique à 3 lettres et crée la catégorie
     */
    public function creerNouvelleCategorie($libelle) {
        $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
        $libelleClean = strtr($libelle, $unwanted_array);
        $baseId = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $libelleClean), 0, 3));
        if (strlen($baseId) < 3) {
            $baseId = str_pad($baseId, 3, 'X');
        }
        
        $id = $baseId;
        $counter = 1;
        while ($this->categorieExiste($id) && $counter < 10) {
            $id = substr($baseId, 0, 2) . $counter;
            $counter++;
        }
        
        $this->ajouterCategorie($id, $libelle);
        return $id;
    }

    private function categorieExiste($id) {
        $req = "SELECT COUNT(*) as nb FROM categorie WHERE id = ?";
        $res = $this->executerRequete($req, array($id));
        $ligne = $res->fetch();
        return $ligne['nb'] > 0;
    }

    /**
     * Vérifie si une catégorie ne contient aucun produit
     */
    public function estCategorieVide($id) {
        $req = "SELECT COUNT(*) as nb FROM produit WHERE idCategorie = ?";
        $res = $this->executerRequete($req, array($id));
        $ligne = $res->fetch();
        return $ligne['nb'] == 0;
    }

    /**
     * Supprime une catégorie
     */
    public function supprimerCategorie($id) {
        $req = "DELETE FROM categorie WHERE id = ?";
        $this->executerRequete($req, array($id));
    }

    /**
     * Modifie le libellé d'une catégorie
     */
    public function modifierCategorie($id, $libelle) {
        $req = "UPDATE categorie SET libelle = ? WHERE id = ?";
        $this->executerRequete($req, array($libelle, $id));
    }

    /**
     * Met à jour le stock d'un produit
     */
    public function mettreAJourStock($idProduit, $stock) {
        $req = "UPDATE produit SET stock = ? WHERE id = ?";
        $this->executerRequete($req, array($stock, $idProduit));
    }
    /**
     * Retourne toutes les associations de produits avec leurs descriptions
     */
    public function getLesAssociations() {
        $req = "SELECT a.idProduit, p1.description as desc1, a.idProduitAssocie, p2.description as desc2 
                FROM associer a
                JOIN produit p1 ON a.idProduit = p1.id
                JOIN produit p2 ON a.idProduitAssocie = p2.id
                ORDER BY a.idProduit";
        $res = $this->executerRequete($req);
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Vérifie si une association existe déjà
     */
    public function associationExiste($id1, $id2) {
        $req = "SELECT COUNT(*) as nb FROM associer WHERE idProduit = ? AND idProduitAssocie = ?";
        $res = $this->executerRequete($req, array($id1, $id2));
        $ligne = $res->fetch();
        return $ligne['nb'] > 0;
    }

    /**
     * Ajoute une nouvelle association
     */
    public function ajouterAssociation($id1, $id2) {
        $req = "INSERT INTO associer (idProduit, idProduitAssocie) VALUES (?, ?)";
        $this->executerRequete($req, array($id1, $id2));
    }

    /**
     * Supprime une association
     */
    public function supprimerAssociation($id1, $id2) {
        $req = "DELETE FROM associer WHERE idProduit = ? AND idProduitAssocie = ?";
        $this->executerRequete($req, array($id1, $id2));
    }

    /**
     * Modifie une association (en réalité on remplace l'ancienne par la nouvelle)
     */
    public function modifierAssociation($ancienId1, $ancienId2, $nouveauId1, $nouveauId2) {
        // Comme c'est une clé composite, on supprime et on réinsère
        $this->supprimerAssociation($ancienId1, $ancienId2);
        $this->ajouterAssociation($nouveauId1, $nouveauId2);
    }

    /**
     * Retourne toutes les commandes triées par ID
     */
    public function getLesCommandes() {
        $req = "SELECT id, dateCommande, nomPrenomClient, adresseRueClient, cpClient, villeClient, mailClient, etat
                FROM commande ORDER BY id DESC";
        $res = $this->executerRequete($req);
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Retourne les articles d'une commande avec les infos produit
     */
    public function getArticlesCommande($idCommande) {
        $req = "SELECT c.idProduit, c.quantite, p.description, p.prix, p.marque, p.idCategorie
                FROM contenir c
                JOIN produit p ON c.idProduit = p.id
                WHERE c.idCommande = ?";
        $res = $this->executerRequete($req, array($idCommande));
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Modifie l'état d'une commande
     */
    public function modifierEtatCommande($idCommande, $etat) {
        $req = "UPDATE commande SET etat = ? WHERE id = ?";
        $this->executerRequete($req, array($etat, $idCommande));
    }

    /**
     * Retourne toutes les promotions programmées
     */
    public function getLesPromotions() {
        $req = "SELECT pr.id, pr.idProduit, pr.dateDebut, pr.dateFin, p.description, p.image 
                FROM promotion pr
                JOIN produit p ON pr.idProduit = p.id
                ORDER BY pr.dateDebut DESC";
        $res = $this->executerRequete($req);
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Vérifie si une promotion (chevauchement) existe déjà pour ce produit
     */
    public function promotionExiste($idProduit, $dateDebut, $dateFin) {
        // Il y a chevauchement si (dateDebutExistante <= dateFinNouvelle) ET (dateFinExistante >= dateDebutNouvelle)
        $req = "SELECT COUNT(*) as nb FROM promotion WHERE idProduit = ? AND dateDebut <= ? AND dateFin >= ?";
        $res = $this->executerRequete($req, array($idProduit, $dateFin, $dateDebut));
        $ligne = $res->fetch();
        return $ligne['nb'] > 0;
    }

    /**
     * Ajoute une nouvelle promotion
     */
    public function ajouterPromotion($idProduit, $dateDebut, $dateFin) {
        $req = "INSERT INTO promotion (idProduit, dateDebut, dateFin) VALUES (?, ?, ?)";
        $this->executerRequete($req, array($idProduit, $dateDebut, $dateFin));
    }

    /**
     * Supprime une promotion
     */
    public function supprimerPromotion($id) {
        $req = "DELETE FROM promotion WHERE id = ?";
        $this->executerRequete($req, array($id));
    }

    /**
     * Nettoie les promotions dont la date de fin est dépassée
     */
    public function nettoyerPromotionsExpirees() {
        $req = "DELETE FROM promotion WHERE dateFin < CURDATE()";
        $this->executerRequete($req);
    }
}
?>
