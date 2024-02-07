<?php

// session start 
session_start();

// Import fichier function article
require_once '/app/env/variable.php';
require_once '/app/request/categories.php';

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

//verification si les champs obligatoire sont remplis
if (
    !empty($_POST['nom']) &&
    !empty($_FILES['image'])
) {
    //on nettoie nos données 
    $nom = strip_tags($_POST['nom']);

    //on verifie que notre Nom n'existe pas deja 
    if (!findOneCategorieByNom($nom)) {
        // a voir comme l'image est obligatoire
        if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] === 0) {
            $imageName = uploadCategorieImage($_FILES['image']);


            // on envoie les données en DB
            if (createCategorie($nom, $imageName)) {
                $_SESSION['message']['success'] = "categorie crée avec succées";

                http_response_code(302);
                header('Location: /admin/categories');
                exit();
            } else {
                $errorMessage = 'Une erreur est survenue';
            }
        } else {
            $errorMessage = 'Image invalide';
        }
    } else {
        $errorMessage = "Le nom est déjà utilisé";
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
    <title>Création categorie | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Création d'une categorie</h1>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" class="form mt-2" method="post" enctype="multipart/form-data">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="nom">Nom de la categorie:</label>
                    <input type="text" name="nom" id="nom" placeholder="nom de la categorie" require>
                </div>

                <div class="group-input">
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                </div>
                <button type="submit" class="btn btn-primary">Créer</button>
            </form>
        </section>
    </main>
</body>

</html>