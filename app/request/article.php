<?php

require_once '/app/conf/mysql.php';

function findAllArticles():array
{
    global $db;

    $sqlStatement = $db -> prepare("SELECT * FROM articles");
    $sqlStatement -> execute();

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
 * @param string $title
 * @param string $description
 * @param boolean $enable
 * @return boolean
 */
function createArticle(string $title, string $description, bool $enable): bool 
{
    global $db;

    try {
            $sqlStatement = $db->prepare("INSERT INTO articles(title, description, enable) VALUES (:title, :description, :enable)");
            $sqlStatement -> execute([
                'title' => $title,
                'description' => $description,
                'enable' => $enable,
            ]);
    } catch(PDOException $error) {
        return false;
    }

    return true;
}