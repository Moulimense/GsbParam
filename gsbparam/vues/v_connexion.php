<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-primary text-white text-center rounded-top-4 py-4">
                    <h3 class="mb-0 fw-bold">Se connecter</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="index.php?uc=connexion&action=validerConnexion">
                        <div class="mb-4">
                            <label for="mail" class="form-label fw-semibold">Adresse Email</label>
                            <input type="email" class="form-control form-control-lg" name="mail" id="mail" placeholder="nom@exemple.com" required>
                        </div>
                        <div class="mb-4">
                            <label for="mdp" class="form-label fw-semibold">Mot de passe</label>
                            <input type="password" class="form-control form-control-lg" name="mdp" id="mdp" placeholder="••••••••" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">Se connecter</button>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="mb-0">Pas encore de compte ? <a href="index.php?uc=connexion&action=demanderInscription" class="text-primary fw-bold text-decoration-none">S'inscrire</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
