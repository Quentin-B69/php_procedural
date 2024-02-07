<?php

require_once '/app/conf/mysql.php';

function findAllCategories(): array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM categories");
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}



/**
 * function to find One categorie by nom
 *
 * @param string $nom
 * @return boolean|array
 */
function findOneCategorieByNom(string $nom): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM categories WHERE nom = :nom");
    $sqlStatement->execute([
        'nom' => $nom,
    ]);

    return $sqlStatement->fetch();
}


/**
 * Undocumented function
 *
 * @param integer $id
 * @return boolean|array
 */
function findOneCategorieById(int $id): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM categories WHERE id = :id");
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
function createCategorie(string $Nom, string $imageName): bool
{
    global $db;


    try {
        $params = [
            'nom' => $Nom,

        ];

        if ($imageName) {
            $query = "INSERT INTO categories(nom, imageName) VALUES (:nom, :imageName)";
            $params['imageName'] = $imageName;
        } else {
            $query = "INSERT INTO categories(nom) VALUES (:nom)";
        }

        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);
    } catch (PDOException $error) {
        return false;
    }

    return true;
}


function updateCategories(int $id, string $nom, ?string $imageName): bool
{
    global $db;

    try {
        $query = "UPDATE categories SET nom = :nom";
        $params = [
            'id' => $id,
            'nom' => $nom,

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



function deleteCategorie(int $id): bool
{
    global $db;

    try {
        $sqlStatement = $db->prepare("DELETE FROM categories WHERE id = :id");
        $sqlStatement->execute([
            'id' => $id,
        ]);
    } catch (PDOException $error) {
        return false;
    }

    return true;
}


function uploadCategorieImage(array $image, ?string $oldImageName = null): bool|string
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

            move_uploaded_file($image['tmp_name'], "/app/uploads/categories/$fileName");

            if ($oldImageName && file_exists("/app/uploads/categories/$oldImageName")) {
                unlink("/app/uploads/categories/$oldImageName");
            }

            return $fileName;
        }
    }

    return false;
}
