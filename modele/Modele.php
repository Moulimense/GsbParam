<?php
/**
 * @file Modele.php
 * @author Marielle Jouin <marielle.jouin@ac-orleans-tours.fr>
 * @version    3.0
  */
  
 /**
 * @class Modele
 * @brief contient les fonctions pour se connecter à la BD et exécuter des requêtes
 */
abstract class Modele{

    private $bdd;

/**
 * executerRequete Execute une requête sql $sql éventuellement paramétrée
 * @param $sql la requête à exécuter
 * @param $params un tableau de paramètres
 * @return les objets résultants de la requête
 */
protected function executerRequete($sql, $params=null){
if ($params == null)
{
    $result=$this->getBdd()->query($sql); // exécution directe
}
else 
{
    $result=$this->getBdd()->prepare($sql); //requête préparée paramétrée
    $result->execute($params);
}
return $result;
}
/**
 * getBdd fournit un objet Pdo $bdd
 * pour effectuer ensuite des requêtes
*/
private function getBdd() {
    require_once('config/.config.php');
    try {
        if ($this->bdd==null)
        $this->bdd = new PDO("mysql:host=".SERVER.";dbname=".BASE,USER,PASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')); 
        $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->bdd;
    } catch (PDOException $e) {
        print "Erreur de connexion PDO ";
        die();
    }
}
 }
?>
