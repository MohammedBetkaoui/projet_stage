<header class="header">
        <div class="header-content">
           <img src="../images/welcome to our teamBienvenue sur notre plateforme de stages.png" alt="">            
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="header-buttons">
                    <a href="/auth/register/register.php" class="btn btn-primary">S'inscrire</a>
                    <a href="/auth/login/login.php" class="btn btn-secondary">Se connecter</a>
                </div>
            <?php endif; ?>
        </div>
    </header>