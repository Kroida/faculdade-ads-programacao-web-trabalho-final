<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login UniFood</title>
    <link rel="stylesheet" href="tela_cadastro.css">
</head>
<body>
    
    <div class="login_container">
        <div class="logo"><img src="Imagens/logo.jpg" alt="Logo principal"></div>

        <h1>Crie sua conta aqui!</h1>

        <form action="processa_login.php" method="POST">

            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Email válido">
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Sua senha">
            </div>
            <div class="support-links"></div>
            <button type="submit" class="btn-submit"> <a href="#"></a>Criar Conta</button>
        </form>
        </div>
    </div>
</body>
</html>