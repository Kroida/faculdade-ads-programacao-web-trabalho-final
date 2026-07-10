<?php

// Finaliza a sessão do usuário e volta para a página inicial.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];

session_destroy();

header("Location: ../index.php"); // ou ../index.php
exit;