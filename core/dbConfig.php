<?php 
session_start();

$host = "127.0.0.1";
$user = "root";
$password = "";      
$dbname = "dequito";

// CRITICAL FIX: Port changed to 3307 based on your XAMPP settings
$dsn = "mysql:host={$host};port=3307;dbname={$dbname}"; 

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    $pdo->exec("SET time_zone = '+08:00';");

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>