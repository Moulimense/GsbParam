<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-primary text-white text-center rounded-top-4 py-4">
                    <h3 class="mb-0 fw-bold">Créer un compte</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="index.php?uc=connexion&action=confirmerInscription">
                        
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label fw-semibold">Nom</label>
                                <input type="text" class="form-control form-control-lg" name="nom" id="nom" placeholder="Votre nom" required>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label for="prenom" class="form-label fw-semibold">Prénom</label>
                                <input type="text" class="form-control form-control-lg" name="prenom" id="prenom" placeholder="Votre prénom" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="rue" class="form-label fw-semibold">Adresse</label>
                            <input type="text" class="form-control form-control-lg" name="rue" id="rue" placeholder="123 Rue de la République" required>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-4">
                                <label for="cp" class="form-label fw-semibold">Code Postal</label>
                                <input type="text" class="form-control form-control-lg" name="cp" id="cp" placeholder="75000" pattern="[0-9]{5}" maxlength="5" title="Le code postal doit contenir exactement 5 chiffres" required>
                            </div>
                            <div class="col-md-8 mt-3 mt-md-0">
                                <label for="ville" class="form-label fw-semibold">Ville</label>
                                <input type="text" class="form-control form-control-lg" name="ville" id="ville" placeholder="Paris" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mail" class="form-label fw-semibold">Adresse Email</label>
                            <input type="email" class="form-control form-control-lg" name="mail" id="mail" placeholder="nom@exemple.com" pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title="Veuillez saisir une adresse email valide (ex: nom@domaine.com ou .fr)" required>
                        </div>

                        <div class="row gx-3 mb-4">
                            <div class="col-md-6">
                                <label for="mdp" class="form-label fw-semibold">Mot de passe</label>
                                <input type="password" class="form-control form-control-lg" name="mdp" id="mdp" placeholder="••••••••" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{12,}" title="Au moins 12 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial" required>
                                <small class="text-muted d-block mt-1" style="font-size: 0.8em;">12 carac. min, 1 Maj, 1 min, 1 chiffre, 1 spécial</small>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label for="mdp2" class="form-label fw-semibold">Confirmer</label>
                                <input type="password" class="form-control form-control-lg" name="mdp2" id="mdp2" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold">S'inscrire</button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>