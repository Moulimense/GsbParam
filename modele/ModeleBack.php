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
    public function ajouterProduit($id, $description, $prix, $image, $idCategorie) {
        $req = "insert into produit (id, description, prix, image, idCategorie) values (?, ?, ?, ?, ?)";
        $this->executerRequete($req, array($id, $description, $prix, $image, $idCategorie));
    }

    /**
     * Modifie un produit existant
     */
    public function modifierProduit($id, $description, $prix, $image, $idCategorie) {
        $req = "update produit set description = ?, prix = ?, image = ?, idCategorie = ? where id = ?";
        $this->executerRequete($req, array($description, $prix, $image, $idCategorie, $id));
    }

    /**
     * Supprime un produit de la base
     */
    public function supprimerProduit($id) {
        $req = "delete from produit where id = ?";
        $this->executerRequete($req, array($id));
    }
}
?>
