<?php

session_start();

require_once '/app/env/variable.php';
require_once '/app/request/article.php';

if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {
    $_SESSION['messages']['error'] = 'Vous n\'avez pas les droits pour cette page';

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

$article = findOneArticleById(isset($_GET['id']) ? $_GET['id'] : 0);

if (!$article) {
    $_SESSION['messages']['error'] = 'Article non trouvé';

    http_response_code(302);
    header('location: /admin/articles');
    exit();
}

// verification de soumission de formulaire
if (
    !empty($_POST['title']) &&
    !empty($_POST['description'])
) {

    // nettoyage des données
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $enable = isset($_POST['enable']) ? 1 : 0;

    $oldTitle = $article['title'];

    if ($oldTitle === $title || !findOneArticleByTitle($title)) {
        if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] === 0) {
            $imageName = uploadArticleImage($_FILES['image'], $article['imageName']);
        }

        if (updateArticle($article['id'], $title, $description, $enable, isset($imageName) ? $imageName : null)) {

            $_SESSION['messages']['succes'] = "Article mis a jour avec succés";
            http_response_code(302);
            header('Location: /admin/articles');
            exit();
        } else {
            $errorMessage = 'Une erreur est survenue, veuillez réessayer';
        }
    } else {
        $errorMessage = 'Le titre est déja utilisé par un autre article';
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
    <title>Modifier un article | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Modifier d'un article</h1>
            <form action="<?= $_SERVER['PHP_SELF'] . '?id=' . $_GET['id']; ?>" class="form" method="post" enctype="multipart/form-data">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="title">Titre:</label>
                    <input type="text" name="title" id="title" placeholder="titre" value="<?= $article['title']; ?>" require>
                </div>
                <div class="group-input">
                    <label for="description">description</label>
                    <textarea name="description" id="description" row="10" placeholder="contenu de l'article" require><?= $article['description']; ?></textarea>
                </div>
                <div class="group-input">
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                    <?php if ($article['imageName']) : ?>
                        <img src="/uploads/articles/<?= $article['imageName']; ?>" alt="" loading="lazy">
                    <?php endif ?>
                </div>
                <div class="group-input checkbox">
                    <input type="checkbox" name="enable" id="enable" <?= $article['enable'] ? 'checked' : null; ?>>
                    <label for="enable">Actif</label>
                </div>
                <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
        </section>
    </main>
</body>

</html>