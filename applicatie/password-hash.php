<?php
require_once 'custom_db_connectie.php';
$verbinding = maakVerbinding();

$sql = "SELECT username, password FROM [User]";
$statement = $verbinding->query($sql);
$users = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $hashed_password = password_hash('wachtwoord', PASSWORD_DEFAULT);//standaard is 'wachtwoord'
    //$hashed_password = password_hash($user ['password'], PASSWORD_DEFAUTL);
    $update_sql = "UPDATE [User] SET password = :password WHERE username = :username";
    $update_statement = $verbinding->prepare($update_sql);
    $update_statement->execute([
        ':password' => $hashed_password,
        ':username' => $user['username']
    ]);
}
// echo "Wachtwoorden zijn gehashed!";
?>