<?php

// session start 
session_start();

// Import fichier function categorie
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

$categorie = findOnecategorieById(isset($_GET['id']) ? $_GET['id'] : 0);

if (!$categorie) {
    $_SESSION['messages']['error'] = 'categorie non trouvé';

    http_response_code(302);
    header('location: /admin/categories');
    exit();
}

// verification si les champs obligatoire sont remplis
if (
    !empty($_POST['nom']) &&
    !empty($_FILES['image'])
) {
    //on nettoie nos données 
    $nom = strip_tags($_POST['nom']);

    $oldNom = $categorie['nom'];

    if ($oldNom === $nom || !findOnecategorieByNom($nom)) {
        if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] === 0) {
            $imageName = uploadCategorieImage($_FILES['image'], $categorie['imageName']);
        }

        if (updateCategories($categorie['id'], $nom, isset($imageName) ? $imageName : null)) {

            $_SESSION['messages']['succes'] = "Categories mise a jour avec succés";
            http_response_code(302);
            header('Location: /admin/categories');
            exit();
        } else {
            $errorMessage = 'Une erreur est survenue, veuillez réessayer';
        }
    } else {
        $errorMessage = 'Le titre est déja utilisé par une autre categories';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = "Veuillez remplir tous les champs obligatoires";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une categorie | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Modifier d'une categorie</h1>
            <form action="<?= $_SERVER['PHP_SELF'] . '?id=' . $_GET['id']; ?>" class="form" method="post" enctype="multipart/form-data">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="nom">Titre:</label>
                    <input type="text" name="nom" id="nom" placeholder="titre" value="<?= $categorie['nom']; ?>" require>
                </div>
                <div class="group-input">
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                    <?php if ($categorie['imageName']) : ?>
                        <img src="/uploads/cate$categories/<?= $categorie['imageName']; ?>" alt="" loading="lazy">
                    <?php endif ?>
                </div>
                <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
        </section>
    </main>
</body>

</html>