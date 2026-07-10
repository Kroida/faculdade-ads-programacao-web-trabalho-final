<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id"])) {
    $_SESSION["erro"] = "Faça login antes de realizar esta ação!";

    header("Location: login.php");
    exit;
}