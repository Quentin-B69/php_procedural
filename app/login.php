<?php

session_start();

require_once '/app/env/variable.php';
require_once '/app/request/users.php';

//on verifie que les données ne sont pas vide 0
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    // on essaie de récupéré l'utilisateur en BDD
    $user = findOneUserByEmail($_POST['email']);

    if ($user) {
        //Utilisateur en BDD
        if (password_verify($_POST['password'], $user['password'])){
            //on connecte l'utilisateur 
            $_SESSION['LOGGED_USER'] = [
                'id' => $user['id'],
                'firstName' => $user['firstName'],
                'laseName' => $user['lastName'],
                'email' => $user['email'],
                'roles' => json_decode( $user['roles'] ?: '[]'),
            ];

            //on redirige sur la page d'accueil
            http_response_code(302);
            header("Location: /");
            exit();
        } else {
            $errorMessage = "Identifiants invalide";
        }
    } else {
        $errorMessage = "Identifiants invalide";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = "veuillez renseignez les champs obligatoire ";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php';?>
        <section class="container mt-2">
            <h1 class="text-center mt-2">Connexion</h1>
            <form action="/login.php" class="form" method="POST">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="john@exemple.com" required>
                </div>
                <div class="group-input">
                    <label for="password">Mots de passe:</label>
                    <input type="password" name="password" id="password" placeholder="S3CR3T" required>
                </div>
                <button type="submit" class="btn btn-primary">Connexion</button>
            </form>
        </section>
    </main>
</body>

</html>