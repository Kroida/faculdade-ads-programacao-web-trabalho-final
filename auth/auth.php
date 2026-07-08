<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id"])) {
    $_SESSION["erro"] = "Faça o login antes de realizar uma compra!";

    header("Location: processaLogin.php");
    exit;

} else {
    $_SESSION["pedidofeito"] = true;

    header("Location: ../index.php");
    exit;
}