<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniFood Início</title>
    <link rel="stylesheet" href="src/styles/style.css">
</head>

<body>

    <?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    ?>

    <header class="banner" id="banner">
        <div class="container">

            <div class="header-top">

                <h1>UniFood</h1>

                <?php if (isset($_SESSION["id"])): ?>

                    <div class="usuario">

                        <span>Olá, <?= htmlspecialchars($_SESSION["nome"]) ?> 👋</span>

                        <a href="layout/logout.php" class="btn-header">Logout</a>

                    </div>

                <?php else: ?>

                    <a href="login.php" class="btn-header">Login</a>

                <?php endif; ?>

            </div>

            <img src="src/img/logo.jpg" alt="Logo UniFood">

            <p>Pediu? Chegou!</p>

        </div>
    </header>