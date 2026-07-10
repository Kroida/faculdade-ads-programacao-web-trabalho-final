<?php

require "auth/authAdm.php";
require "config/admFunctions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["acao"])) {

    if ($_POST["acao"] === "cadastrar") {
        $resultado = insertUser($pdo);

        if ($resultado["ok"]) {
            $_SESSION["sucesso"] = $resultado["mensagem"];
        } else {
            $_SESSION["erro"] = implode("<br>", $resultado["erros"]);
        }

        header("Location: adm.php");
        exit;
    }

    if ($_POST["acao"] === "editar") {
        $resultado = updateUser($pdo, $_POST["id"] ?? 0);

        if ($resultado["ok"]) {
            $_SESSION["sucesso"] = $resultado["mensagem"];
        } else {
            $_SESSION["erro"] = implode("<br>", $resultado["erros"]);
        }

        header("Location: adm.php");
        exit;
    }

    if ($_POST["acao"] === "deletar") {
        $resultado = deleteUser($pdo, $_POST["id"] ?? 0);

        if ($resultado["ok"]) {
            $_SESSION["sucesso"] = $resultado["mensagem"];
        } else {
            $_SESSION["erro"] = $resultado["mensagem"];
        }

        header("Location: adm.php");
        exit;
    }
}

require "includes/header.php";

?>

<main>
    <section class="form-section admin-section" id="formulario">
        <div class="container" id="login">
            <h2>Painel do administrador 👑</h2>
            <p>Olá, senhor <?= htmlspecialchars($_SESSION["name"]) ?> 👋</p>

            <?php if (isset($_SESSION["erro"])): ?>
                <div class="alerta-erro">
                    <p><?= htmlspecialchars($_SESSION["erro"]) ?></p>
                </div>
                <?php unset($_SESSION["erro"]); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION["sucesso"])): ?>
                <div class="alerta-sucesso">
                    <p><?= htmlspecialchars($_SESSION["sucesso"]) ?></p>
                </div>
                <?php unset($_SESSION["sucesso"]); ?>
            <?php endif; ?>

            <div class="box-cadastro-admin">
                <h3>➕ Cadastrar novo usuário</h3>

                <form method="POST" class="form-admin-criar">
                    <input type="hidden" name="acao" value="cadastrar">

                    <input type="text" name="name" placeholder="Nome completo" required>

                    <input type="email" name="email" placeholder="E-mail" required>

                    <input type="password" name="password" placeholder="Senha" required>

                    <button type="submit" name="cadastrar" value="1" class="btn-criar">
                        ➕ Cadastrar usuário
                    </button>
                </form>
            </div>

            <table class="tabela-admin">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Status</th>
                        <th>Data de Cadastro</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $usuarios = listUsers($pdo);

                    foreach ($usuarios as $usuario):
                        $idUsuario = (int) $usuario["id"];
                        ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($usuario["id"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($usuario["name"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($usuario["email"]) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($usuario["status"]) ?>
                            </td>

                            <td>
                                <?= date("d/m/Y", strtotime($usuario["created_at"])) ?>
                            </td>

                            <td class="col-acoes">
                                <?php if ($idUsuario === 1): ?>

                                    <span class="badge-admin">
                                        🛡️ Administrador protegido
                                    </span>

                                <?php else: ?>

                                    <div class="acoes-usuario">
                                        <details class="editar-user-box">
                                            <summary class="btn-editar">
                                                ✏️ Editar
                                            </summary>

                                            <form method="POST" class="form-editar-admin">
                                                <input type="hidden" name="acao" value="editar">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($usuario["id"]) ?>">

                                                <input type="text" name="name" value="<?= htmlspecialchars($usuario["name"]) ?>"
                                                    placeholder="Nome" required>

                                                <input type="email" name="email"
                                                    value="<?= htmlspecialchars($usuario["email"]) ?>" placeholder="E-mail"
                                                    required>

                                                <input type="password" name="password" placeholder="Nova senha opcional">

                                                <select name="status">
                                                    <option value="active" <?= $usuario["status"] === "active" ? "selected" : "" ?>>
                                                        Ativo
                                                    </option>

                                                    <option value="inactive" <?= $usuario["status"] === "inactive" ? "selected" : "" ?>>
                                                        Inativo
                                                    </option>

                                                    <option value="banned" <?= $usuario["status"] === "banned" ? "selected" : "" ?>>
                                                        Banido
                                                    </option>
                                                </select>

                                                <button type="submit" name="atualizar" value="1" class="btn-salvar-edicao">
                                                    💾 Salvar alterações
                                                </button>
                                            </form>
                                        </details>

                                        <form method="POST" class="form-deletar-admin"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                            <input type="hidden" name="acao" value="deletar">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($usuario["id"]) ?>">

                                            <button type="submit" class="btn-deletar">
                                                🗑️ Deletar
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php require "includes/footer.php"; ?>