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

$categorie = findOnecategorieById(isset($_POST['id']) ? $_POST['id'] : 0);

if ($categorie) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
        if (deleteCategorie($categorie['id']))  { 
            if ($categorie['imageName'] && 
            file_exists("/app/uploads/categories/$categorie[imageName]")
            ) {
                unlink("/app/uploads/categories/$categorie[imageName]");
            }      
            $_SESSION['messages']['success'] = "categorie supprimé avec succés ";
        } else {
            $_SESSION['messages']['error'] = " Une erreur est survenue, veuillez réessayer";
        }
    } else {
        $_SESSION['messages']['error'] = "Token CSRF invalide";
    }
} else {
    $_SESSION['messages']['error'] = "categorie non trouvé";
}

http_response_code(302);
header("Location: /admin/categories");
exit();
