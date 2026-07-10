<?php

// Protege páginas que exigem usuário logado.
// Se não existir sessão de usuário, redireciona para o login.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id"])) {
    $_SESSION["erro"] = "Faça login antes de realizar esta ação!";

    header("Location: login.php");
    exit;
}