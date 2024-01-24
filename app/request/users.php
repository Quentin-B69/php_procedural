<?php

require_once '/app/conf/mysql.php';

function findAllUsers(): array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM users");
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}

/**
 * function to find user by email adress
 *
 * @param string $email
 * @return bool|array
 */
function findOneUserByEmail(string $email): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT firstName, lastName, email, password FROM users WHERE email = :email");
    $sqlStatement->execute([
        'email' => $email,
    ]);

    return $sqlStatement->fetch();
}

function createUser(string $firstName, string $lastName, string $email, string $password): bool
{
    global $db;

    try {
        $query = "INSERT INTO users(firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)";
        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'password' => $password,
        ]);
    } catch (PDOException $error) {
        return false;
    }
    return true;
}
