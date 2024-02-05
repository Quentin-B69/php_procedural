<header class="navbar">
    <p class="navbar-brand">
        <a href="<?= $rootUrl ?>">My first app PHP</a>
    </p>
<ul class="navbar-link">
    <li class="navbar-link">accueil</li>
    <li class="navbar-link">articles</li>
</ul>
<ul class="navbar-links navbar-btn">
    <li class="navbar-link">
        <?php if (!empty($_SESSION['LOGGED_USER'])): ?>
            <?php if (in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])): ?>
                    <a href="/admin" class="btn btn-secondary">Admin</a>
                <?php endif; ?>
            <a href="/logout.php" class="btn btn-danger">Se Deconnecter</a>
        <?php else : ?>
            <a href="/register.php" class="btn btn-light">S'inscrire</a>
            <a href="/login.php" class="btn btn-secondary">Se Connecter</a>
        <?php endif;?>
    </li>
</ul>
</header>