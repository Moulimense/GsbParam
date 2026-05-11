<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="text-center">Connexion Administration</h3>
            <form action="index.php?uc=administrer&action=validerConnexion" method="post" class="border p-4 shadow-sm">
                <div class="mb-3">
                    <label class="form-label">Identifiant</label>
                    <input type="text" name="login" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="mdp" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
        </div>
    </div>
</div>
