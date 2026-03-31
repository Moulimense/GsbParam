<style>
/* Style pour la page de choix (Visiteur / Responsable) */
#choix-entree {
    text-align: center;
    margin-top: 100px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

#choix-entree h2 {
    color: #2c3e50;
    margin-bottom: 40px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* Suppression des emojis et style épuré */
#choix-entree .btn-gsb {
    display: inline-block;
    width: 280px;
    padding: 15px 25px;
    margin: 15px;
    font-family: Arial, sans-serif;
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    border-radius: 4px;
    transition: background-color 0.3s, transform 0.1s;
    border: none;
}

/* Bouton Visiteur : Bleu GSB */
#choix-entree .btn-visiteur {
    background-color: #0056b3;
    color: #ffffff;
}

#choix-entree .btn-visiteur:hover {
    background-color: #004494;
}

/* Bouton Responsable : Gris Anthracite Professionnel */
#choix-entree .btn-admin {
    background-color: #343a40;
    color: #ffffff;
}

#choix-entree .btn-admin:hover {
    background-color: #23272b;
}

/* Effet au clic pour plus de réalisme */
#choix-entree .btn-gsb:active {
    transform: translateY(2px);
}
</style>

<div id="choix-entree">
    <h2>Accès à la plateforme GSB</h2>
    <div class="container-boutons">
        <a href="index.php?uc=visiteur&action=nosProduits" class="btn-gsb btn-visiteur">Accès Visiteur</a>
        <a href="index.php?uc=administrer" class="btn-gsb btn-admin">Espace Responsable</a>
    </div>
</div>
