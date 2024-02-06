<?php

// session start 
session_start();

// Import fichier function article
require_once '/app/env/variable.php';
require_once '/app/request/article.php';

//Vérifier les droits utilisateur connecter 
if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {
    $_SESSION['messages']['error'] = 'vous n\'avez pas les droits pour cette page';

    http_response_code(302);
    header("Location: /login");
    exit();
}

// Vérification si les champs obligatoire sont remplis 
if (
    !empty($_POST['title']) &&
    !empty($_POST['description'])
) {
    // on nettoie nos données 
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $enable = isset($_POST['enable']) ? true : false;

    // verifier si le titre n'existe pas
    if (!findOneArticleByTitle($title)) {
        // on envoie les données en DB 
        if (createArticle($title, $description, $enable)) {
            $_SESSION['message']['success'] = "article crée avec succées";

            http_response_code(302);
            header('Location: /admin/articles');
            exit();
        } else {
            $errorMessage = 'Une erreur est survenue';
        }
    } else {
        $errorMessage = 'Le titre est deja utilisé par un autre article';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = 'veuillez remplir tout les champs obligatoires';
}

// Partie HTML de la page
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création article | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Création d'un article</h1>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" class="form" method="post">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" placeholder="titre" require>
                </div>
                <div class="group-input">
                    <label for="description">description</label>
                    <textarea name="description" id="description" row="10" placeholder="contenu de l'article" require></textarea>
                </div>
                <div class="group-input">
                    <input type="checkbox" name="enable" id="enable">
                    <label for="enable">Actif</label>
                </div>
                <button type="submit" class="btn btn-primary">Créer</button>
            </form>
        </section>
    </main>
</body>

</html>