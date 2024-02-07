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

$_SESSION['token'] = bin2hex(random_bytes(50));

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Categorie </title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
    <link rel="stylesheet" href="<?= $cssPath; ?>index.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <?php require_once '/app/layout/messages.php'; ?>
    <main>
        <section class="container mt-2">
            <h1 class="text-center">Admin des Categorie</h1>
            <a href="/admin/categories/create.php" class="btn btn-primary ">Créer une Categorie</a>
            <div class="card-list mt-2">
                <?php foreach (findAllCategories() as $categorie) : ?>
                    <div class="card">
                        <?php if ($categorie['imageName']) : ?>
                            <img src="/uploads/categories/<?= $categorie['imageName']; ?>" alt="" loading="lazy">
                        <?php endif; ?>

                        <h2 class="card-header"><?= $categorie['nom']; ?></h2>

                        <div class="card-btn">
                            <a href="/admin/categories/update.php?id=<?= $categorie['id']; ?>" class="btn btn-primary">Editer</a>
                            <form action="/admin/categories/delete.php" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette categorie ?')">
                                <input type="hidden" name="id" value="<?= $categorie['id']; ?>">
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