<?php

require "../config/conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"];

if ($email === "" || $password === "") {
    $_SESSION["erro"] = "Preencha todos os campos.";
    header("Location: ../login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["erro"] = "E-mail inválido.";
    header("Location: ../login.php");
    exit;
}

$sql = "
SELECT *
FROM users
WHERE email = :email
";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ":email" => $email
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {

        $_SESSION["erro"] = "E-mail não encontrado.";

        header("Location: ../login.php");
        exit;
    }

    if (!password_verify($password, $usuario["password_hash"])) {

        $_SESSION["erro"] = "Senha incorreta.";

        header("Location: ../login.php");
        exit;
    }

    $_SESSION["id"] = $usuario["id"];
    $_SESSION["profile_image"] = $usuario["profile_image"];
    $_SESSION["name"] = $usuario["name"];
    $_SESSION["email"] = $usuario["email"];

    header("Location: ../index.php");
    exit;
} catch (PDOException $e) {
    $_SESSION["erro"] = "Erro ao tentar fazer login. Tente novamente.";

    header("Location: ../login.php");
    exit;
}
