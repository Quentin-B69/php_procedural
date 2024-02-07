<?php

require_once '/app/conf/mysql.php';

function findAllArticles(): array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM articles");
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}

function findAllArticlesWithAutor(): array
{
    global $db;

    $query = "SELECT a.id, a.title, a.description, a.createdAt, a.enable , a.imageName, u.firstName, u.lastName, c.nom FROM articles a JOIN users u ON a.auteurId = u.id LEFT JOIN categories c ON a.categoriesId = c.id";

    $sqlStatement = $db->prepare($query);
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}




/**
 * Undocumented function
 *
 * @param string $date
 * @param string $format
 * @return string
 */
function convertDateArticle(string $date, string $format): string
{
    return (new DateTime($date))->format($format);
}

/**
 * function to find One article by title
 *
 * @param string $title
 * @return boolean|array
 */
function findOneArticleByTitle(string $title): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM articles WHERE title = :title");
    $sqlStatement->execute([
        'title' => $title,
    ]);

    return $sqlStatement->fetch();
}


/**
 * Undocumented function
 *
 * @param integer $id
 * @return boolean|array
 */
function findOneArticleById(int $id): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM articles WHERE id = :id");
    $sqlStatement->execute([
        'id' => $id,
    ]);

    return $sqlStatement->fetch();
}

/**
 * Undocumented function
 *
 * @param string $title
 * @param string $description
 * @param boolean $enable
 * @param ?string $imageName
 * @return boolean
 */
function createArticle(string $title, string $description, bool $enable, int $auteurId, ?string $imageName): bool
{
    global $db;


    try {
        $params = [
            'title' => $title,
            'description' => $description,
            'enable' => $enable,
            'auteurId' => $auteurId
        ];

        if ($imageName) {
            $query = "INSERT INTO articles(title, description, enable, imageName, auteurId) VALUES (:title, :description, :enable, :imageName, :auteurId)";
            $params['imageName'] = $imageName;
        } else {
            $query = "INSERT INTO articles(title, description, enable, auteurId) VALUES (:title, :description, :enable, :auteurId)";
        }

        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);
    } catch (PDOException $error) {
        return false;
    }

    return true;
}


function updateArticle(int $id, string $title, string $description, int $enable, ?string $imageName): bool
{
    global $db;

    try {
        $query = "UPDATE articles SET title = :title, description = :description, enable = :enable";
        $params = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'enable' => $enable,
        ];

        if ($imageName) {
            $query .= ", imageName = :imageName";
            $params['imageName'] = $imageName;
        }

        $query .= " WHERE id = :id";


        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);
    } catch (PDOException $error) {
        return false;
    }
    return true;
}

function deleteArticle(int $id): bool
{
    global $db;

    try {
        $sqlStatement = $db->prepare("DELETE FROM articles WHERE id = :id");
        $sqlStatement->execute([
            'id' => $id,
        ]);
    } catch (PDOException $error) {
        return false;
    }

    return true;
}


function uploadArticleImage(array $image, ?string $oldImageName = null): bool|string
{
    if ($image['size'] < 16000000) {
        $fileInfo = pathinfo($image['name']);

        $extension = $fileInfo['extension'];
        $extensionAllowed = ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'];

        if (in_array($extension, $extensionAllowed)) {
            $fileName = $fileInfo['filename'] .
                (new DateTime())->format('_Y-m-d_H:i:s') .
                '.' .
                $extension;

            move_uploaded_file($image['tmp_name'], "/app/uploads/articles/$fileName");

            if ($oldImageName && file_exists("/app/uploads/articles/$oldImageName")) {
                unlink("/app/uploads/articles/$oldImageName");
            }

            return $fileName;
        }
    }

    return false;
}
