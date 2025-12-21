<?php
require_once __DIR__ . '/vendor/autoload.php';


$host = 'localhost';
$username = 'root';
$password = 'mysql';
$db = 'php_blog';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

//try {
//    $pdo = new PDO(dsn: $dsn, username: $username, password: $password);
//    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    $sql = 'INSERT INTO users(user_fname, user_lname, user_username, user_email, user_password) VALUES(?,?, ?, ?, ?);';
//    $result = $pdo->prepare($sql)->execute(['Jane', 'Doe', 'jdoe54', 'jane.doe@mail.com', password_hash('password123', PASSWORD_BCRYPT)]);
//    if ($result) {
//        echo "row inserted\n";
//    } else {
//        echo "row not inserted\n";
//    }
//} catch (Exception $e) {
//    print_r($e->getMessage());
//}

$db = \Database\Database::init(dsn: $dsn, username: $username, password: $password, options: [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
]);

try{
    $result = $db->one('SELECT * FROM users ORDER BY created_at DESC LIMIT 1');
}catch (PDOException $e){
    $result = $e->getMessage();
}


