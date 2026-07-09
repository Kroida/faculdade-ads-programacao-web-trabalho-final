<?php

/*
As partes comentadas são para caso você esteja usando xamp
*/

// $host = 'localhost';
$host = '127.0.0.1';
$port = '3307';
$db   = 'desgraca';
$user = 'goku';
$pass = '4321';

try {
    $pdo = new PDO(
        // "mysql:host=$host;dbname=$db;charset=utf8mb4",
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('Erro de conexão: ' . $e->getMessage());
}