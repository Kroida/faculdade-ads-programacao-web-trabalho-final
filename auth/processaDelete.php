<?php

require "../config/conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../processaUpdate.php");
    exit;
}

$id = $_SESSION["id"];

$sql = "
    UPDATE users
    SET deleted_at = NOW(),
        status = 'inactive'
    WHERE id = :id
";

$params = [
    ":id" => $id
];

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $_SESSION = [
        "sucesso" => "Conta excluída com sucesso!"
    ];

    header("Location: ../login.php");
    exit;

} catch (PDOException $e) {
    $_SESSION["erro"] = "Erro ao excluir a conta.";

    header("Location: ../dadosCliente.php");
    exit;
}