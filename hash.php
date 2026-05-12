<?php
// Ton mot de passe actuel en clair
$pass = "NearlyTheBest$280@";

// On génère le hash
$hash = password_hash($pass, PASSWORD_DEFAULT);

// Affiche le résultat pour le copier
echo "Copie ce hash dans ta base de données : <br>" . $hash;
?>