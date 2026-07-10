<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id"]) || (int) $_SESSION["id"] !== 1) {
    $_SESSION["erro"] = "Volta pro teu canto meu, aqui não é da tua laia";

    header("Location: login.php");
    exit;
}