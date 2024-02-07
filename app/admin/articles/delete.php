<?php
session_start();

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

$article = findOneArticleById(isset($_POST['id']) ? $_POST['id'] : 0);

if ($article) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
        if (deleteArticle($article['id']))  { 
            if ($article['imageName'] && 
            file_exists("/app/uploads/articles/$article[imageName]")
            ) {
                unlink("/app/uploads/articles/$article[imageName]");
            }      
            $_SESSION['messages']['success'] = "article supprimé avec succés ";
        } else {
            $_SESSION['messages']['error'] = " Une erreur est survenue, veuillez réessayer";
        }
    } else {
        $_SESSION['messages']['error'] = "Token CSRF invalide";
    }
} else {
    $_SESSION['messages']['error'] = "article non trouvé";
}

http_response_code(302);
header("Location: /admin/articles");
exit();
