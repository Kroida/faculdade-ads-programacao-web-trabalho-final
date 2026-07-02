<?php

session_start();

require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$email = trim($_POST["email"]);
$password = $_POST["password"];

$sql = "
SELECT *
FROM users
WHERE email = :email
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ":email" => $email
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {

    $_SESSION["erro"] = "E-mail não encontrado.";

    header("Location: login.php");
    exit;
}

if (!password_verify($password, $usuario["password_hash"])) {

    $_SESSION["erro"] = "Senha incorreta.";

    header("Location: login.php");
    exit;
}

$_SESSION["id"] = $usuario["id"];
$_SESSION["nome"] = $usuario["name"];
$_SESSION["email"] = $usuario["email"];

header("Location: index.php");
exit;