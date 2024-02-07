<?php

require_once '/app/conf/mysql.php';

function findAllUsers(): array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM users");
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}



function findOneUserById(int $id): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM users WHERE id = :id");
    $sqlStatement ->execute([
        'id'=>$id
    ]);

    return $sqlStatement->fetch();
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

    $sqlStatement = $db->prepare("SELECT id, firstName, lastName, email, password, roles FROM users WHERE email = :email");
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

/**
 * function to update a user in DB 
 * 
 * @param integer $id
 * @param string $firstName
 * @param string $lastName
 * @param string $email
 * @param ?array $roles
 * @return boolean
 */

function updateUser(int $id, string $firstName, string $lastName , string $email, ?array $roles): bool
{
    global $db;

    try {
        $sqlStatement = $db->prepare("UPDATE users SET firstName = :firstName, lastName = :lastName, email = :email, roles = :roles  WHERE id = :id");
        $sqlStatement->execute([
            'id' => $id,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'roles' => $roles ? json_encode($roles) : null
        ]);
    }   catch(PDOException $error) {
        return false;
    }

    return true;
}

/**
 * Function to delete a user from DB 
 * 
 * @param integer $id
 * @return boolean
 */

function deleteUser(int $id):bool
{
    global $db;

    try{
            $sqlStatement = $db->prepare("DELETE from users where id = :id");
            $sqlStatement -> execute([
                'id' => $id,
            ]);
    }   catch(PDOException $error) {
        return false;
    }

    return true;
}