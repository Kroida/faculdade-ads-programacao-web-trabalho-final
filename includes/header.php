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

    // Garante que as informações do usuário logado estejam disponíveis no header.
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    ?>

    <header class="header">
        <div class="container">
            <div class="header-info">

                <a href="index.php"><h1>UniFood</h1></a>

                <?php // Se existir sessão, mostra dados do usuário; caso contrário, mostra login. ?>
                <?php if (isset($_SESSION["id"])): ?>

                    <div class="usuario">

                        <span>Olá, <?= htmlspecialchars($_SESSION["name"]) ?> 👋</span>

                        <a href="auth/logout.php" class="btn-header">Logout</a>
                        <a href="dadosCliente.php" class="btn-usuario" title="Mostrar dados"><img src="<?= htmlspecialchars($_SESSION["profile_image"]) ?>" alt="Foto do usuário"></a>

                    </div>

                <?php else: ?>

                    <a href="login.php" class="btn-header">Login</a>

                <?php endif; ?>

            </div>
        </div>
    </header>