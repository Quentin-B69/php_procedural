<?php
session_start();

require_once '/app/env/variable.php';

// on verifie qu'on a uploadé une image et qu'il n'y a pas d'erreurs
if (!empty($_FILES['image']) && $_FILES['image']['error'] === 0) {
    // on vérifie la taille du fichier 
    if ($_FILES['image']['size'] < 16000000) {
        // on verifie l'extention du ficher
        $fileInfo = pathinfo($_FILES['image']['name']);

        //on recupere l'extentsion du fichier uploadé
        $extension = $fileInfo['extension'];

        //on définit les extensions autorisées 
        $extensionAllowed = ['jpg', 'png', 'jpef', 'svg', 'webp', 'gif'];

        //On vérifie que l'extension du fichier est autorisé
        if (in_array($extension, $extensionAllowed)) {
            $fileName = $fileInfo['filename']
                . '_' .
                (new DateTime())->format('Y-m-d_H:m:s')
                . '.' . $extension;

            move_uploaded_file($_FILES['image']['tmp_name'], "/app/uploads/$fileName");
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/Layout/header.php'; ?>
    <main>
        <h1>Votre demande de contact </h1>
        <?php var_dump($_FILES); ?>
        <?php if (!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['message'])) : ?>
            <div class="card">
                <p>prenom <?= strip_tags($_POST['prenom']); ?></p>
                <p>nom <?= htmlspecialchars($_POST['nom']); ?></p>
                <p>Message <?= $_POST['message']; ?></p>
            </div>
        <?php else : ?>
            <div class="alert alert-danger"> veuillez soumettre le formulaire </div>
        <?php endif; ?>

    </main>
</body>

</html>