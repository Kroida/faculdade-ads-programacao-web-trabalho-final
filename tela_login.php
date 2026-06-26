<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login UniFood</title>
    <link rel="stylesheet" href="tela_login.css">
</head>
<body>
    
    <div class="login_container">
        <div class="logo"><img src="Imagens/logo.jpg" alt="Logo principal"></div>

        <h2>Fazer Login</h2>
        <p>Acesse sua conta para fazer seu pedido</p>

        <form action="processa_login.php" method="POST">

            <div class="input-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Email válido" required>
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
            </div>

            <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <div class="support-links">
            <a href="#">Esqueci minha senha</a>
            <span>Ainda não possui conta? <a href="#">Cadastre-se</a></span>
        </div>
    </div>
</body>
</html>