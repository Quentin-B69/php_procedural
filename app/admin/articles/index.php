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

$_SESSION['token'] = bin2hex(random_bytes(50));

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin article </title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
    <link rel="stylesheet" href="<?= $cssPath; ?>index.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <?php require_once '/app/layout/messages.php'; ?>
    <main>
        <section class="container mt-2">
            <h1 class="text-center">Admin des Article</h1>
            <a href="/admin/articles/create.php" class="btn btn-primary ">Créer un article</a>
            <div class="card-list mt-2">
                <?php foreach (findAllArticlesWithAutor() as $article) : ?>

                    <div class="card">
                        <?php if($article['imageName']):?>
                            <img src="/uploads/articles/<?= $article['imageName']; ?>" alt="" loading="lazy">
                        <?php endif; ?>
                        <h2 class="card-header"><?= $article['title']; ?></h2>
                        <em><strong>Date:</strong> <?= convertDateArticle($article['createdAt'], 'd/m/Y'); ?></em>
                        <p><strong>Description:</strong> <?= substr($article['description'], 0, 150). '...'; ?></p>
                        <em><strong>Auteur:</strong> <?= "$article[firstName] $article[lastName]"; ?> </em>
                        <p><em><strong>Categorie:</strong> <?= "$article[nom] "; ?> </em></p>
                        <div class="card-btn">
                            <a href="/admin/articles/update.php?id=<?= $article['id']; ?>" class="btn btn-primary">Editer</a>
                            <form action="/admin/articles/delete.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette article ?')">
                                <input type="hidden" name="id" value="<?= $article['id']; ?>">
                                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                <button type="submit" class="btn btn-danger">supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

</body>

</html>