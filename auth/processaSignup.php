<?php

require "../config/conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$rawPassword = $_POST["password"] ?? "";
$profileImage = "uploads/default/default.jpg";

if ($name === "" || $email === "" || $rawPassword === "") {
    $_SESSION["erro"] = "Preencha todos os campos.";
    header("Location: ../login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["erro"] = "E-mail inválido.";
    header("Location: ../login.php");
    exit;
}

$passwordHash = password_hash(
    $rawPassword,
    PASSWORD_DEFAULT
);

$sql = "

INSERT INTO users

(name, email, password_hash, profile_image)

VALUES

(:name, :email, :password, :profile_image)

";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ":name" => $name,
        ":email" => $email,
        ":password" => $passwordHash,
        ":profile_image" => $profileImage
    ]);

    $userId = $pdo->lastInsertId();
    $_SESSION["id"] = $userId;
    $_SESSION["profile_image"] = $profileImage;
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;

    $_SESSION["sucesso"] = "Conta criada com sucesso!";

    header("Location: ../index.php");
    exit;
} catch (PDOException $e) {
    if ($e->getCode() === "23000") { // violação de constraint UNIQUE
        $_SESSION["erro"] = "Este e-mail já está cadastrado.";
        header("Location: ../login.php");
        exit;
    }
    throw $e; // outros erros continuam subindo — não engula erro que você não sabe tratar
}

