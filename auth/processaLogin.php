<?php

// Processa o formulário de login e cria a sessão do usuário autenticado.

require "../config/conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bloqueia acesso direto pelo navegador; este arquivo só aceita envio via POST.
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
AND deleted_at IS NULL
AND status = 'active'
";

$stmt = $pdo->prepare($sql);

try {
    // O e-mail entra como parâmetro para evitar SQL Injection.
    $stmt->execute([
        ":email" => $email
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {

        $_SESSION["erro"] = "E-mail não encontrado.";

        header("Location: ../login.php");
        exit;
    }

    // Compara a senha digitada com o hash salvo no banco.
    if (!password_verify($password, $usuario["password_hash"])) {

        $_SESSION["erro"] = "Senha incorreta.";

        header("Location: ../login.php");
        exit;
    }

    // Salva na sessão os dados usados pelo header e pelas páginas protegidas.
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
