<?php

// Valida se o usuário está logado antes de finalizar um pedido.
// O projeto ainda não persiste pedidos no banco; ele usa uma flag de sessão para exibir o alerta.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id"])) {
    $_SESSION["erro"] = "Faça login antes de realizar uma compra!";

    header("Location: ../login.php");
    exit;

} else {
    $_SESSION["pedidofeito"] = true;

    header("Location: ../index.php");
    exit;
}