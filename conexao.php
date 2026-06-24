<?php
$host = 'localhost';
$db   = 'desgraca';
$user = 'goku';
$pass = '4321';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "<h1>Banco funfando</h1>";
} catch (PDOException $e) {
    die('Erro de conexão: ' . $e->getMessage());
}