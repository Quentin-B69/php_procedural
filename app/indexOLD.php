<!-- <?php echo 'hello world'; ?>
<? echo'hello world'; ?>
<?= 'hello world'; ?> -->




<?php
//commentaire une ligne 
/**
 * comantaire
 * sur 
 * plusieurs
 * ligne
 */

// $prenom = "Quentin";
// $nom = "Bret" ; 

// $nomComplet = $prenom . " " . $nom;
// $nomComplet = "$prenom  $nom";

// echo $nomComplet;

// $num1 = 10;
// $num2 = 20;

// $resultat = $num1 + $num2;

// echo $resultat ;

// $num1 = 10;

// $num1 += 20;


// $age = 26;

// if ($age >= 18 ){
//     echo "vous etes majeur";
// } else if ($age >=12) {
//     echo "vous etes ado"
// } else {
//     echo "vous etes mineur";
// }

// echo $age >= 18 ? 'vous etes majeur' : 'vous etes mineur';


// pour avoir tout les infos sur le serveur ou l'on est 
// phpinfo();

// $isAutorise = true ;
// $isProprietaire = true ;
// if ($isAutorise && $isProprietaire) {
//     echo 'autorisé';
// } else {
//     echo 'non autorisé';
// }

// $age = 26;

// if ($age >= 18){
//     echo '<h1>vous etes majeur </h1>';
// } else {
//     echo '<h1>vous etes mineur </h1>';
// }


// $users = ['Pierre', 'Paul', 'Jacques'];

// echo $users[0];

// foreach ($users as $users) {
//     echo $users;
// }

// $user1 = ['Pierre', 'Bertrand', 26];
// $user2 = ['Paul', 'Dupond', 46];
// $user3 = ['Jacques', 'Dupont', 19];

// $users = [$user1, $user2, $user3];

// echo $users [1][1];

// $user1 = [
//     'prenom' => 'Pierre',
//     'nom' => 'Bertrand',
//     'age' => 26,
// ];

// echo array_key_exists ( key: 'prenom', array: $user1);
// echo in_array( needle: 'Pierre', haystack: $user1);
// echo array_search(needle: 'Pierre', haystack: $user1);

$users = [ [
        "prenom" => "Pierre",
        "nom" => 'Bertrand',
        "age" => 24,
        "actif" => true,
    ],
    [
        "prenom" => "Paul",
        "nom" => 'Dupont',
        "age" => 33,
        "actif" => false,
    ]
];

foreach ($users as $user) {
    if($user['actif']) {
        echo "$user[prenom] $user[nom]";
    }
}

// function bonjour(): string
// {
//     return 'hello';
// }

// echo bonjour();

function addition(float $val1, float $val2): float
{
    return $val1 + $val2;
}

echo addition(10, 30);