<?php

session_start();

require_once '/app/env/variable.php';
require_once '/app/request/users.php';

// on verifie que les donnée ne sont pas vides 
if (
    !empty($_POST['firstName']) &&
    !empty($_POST['lastName']) &&
    !empty($_POST['email']) &&
    !empty($_POST['password'])
) {
    // Nettoyer les données 
    $firstName = strip_tags($_POST['firstName']);
    $lastName = strip_tags($_POST['lastName']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

    //gerez les erreurs utilisateurs 
    if ($email) {
        //l'email est ok, on vérifie s'il n'existe pas en BDD
        if (!findOneUserByEmail($email)){
            //on crée l'utilisateurs en BDD
            if (createUser( $firstName, $lastName, $email, $password)) {
                //On redirige vers la page de connexion 
                http_response_code(302);
                header("Location: /login.php");
                exit();
            } else {
                $errorMessage = "Une erreur est survenue, veuillez reéssayer";
            }
        }   else {
            $errorMessage = "L'email est déja utilisé par un autre compte";
        }
    } else {
        $errorMessage = "veuillez remplir un email valide";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = 'Veuillez remplir tous les champs obligatoire';
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container mt-2">
            <h1 class="text-center">Inscription</h1>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" classe="form" method="post">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="firstName">Prenom:</label>
                    <input type="text" name="firstName" id="firstName" placeholder="John" require>
                </div>
                <div class="group-input">
                    <label for="lastName">Nom:</label>
                    <input type="text" name="lastName" id="lastName" placeholder="Doe" require>
                </div>
                <div class="group-input">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="John@exemple.com" require>
                </div>
                <div class="group-input">
                    <label for="password">Mots de passe :</label>
                    <input type="password" name="password" id="password" placeholder="S3CR3T" require>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
        </section>
    </main>
</body>

</html>