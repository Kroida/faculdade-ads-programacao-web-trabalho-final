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

    if ($_POST["acao"] === "editar_destaque") {
        $resultado = updateDestaque($pdo, $_POST["id"] ?? 0);

        if ($resultado["ok"]) {
            $_SESSION["sucesso"] = $resultado["mensagem"];
        } else {
            $_SESSION["erro"] = implode("<br>", $resultado["erros"]);
        }

        header("Location: adm.php#destaques-admin");
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


            <section class="admin-destaques" id="destaques-admin">
                <div class="admin-destaques-header">
                    <h3>🍔 Editar destaques da página inicial</h3>
                    <p>Altere os cards abaixo e a página inicial muda automaticamente.</p>
                </div>

                <?php foreach (listDestaques($pdo) as $destaque): ?>
                    <article class="admin-destaque-card">
                        <div class="admin-destaque-preview">
                            <section class="<?= htmlspecialchars(classesDestaque($destaque)) ?>" id="preview-<?= htmlspecialchars($destaque["html_id"]) ?>">
                                <div class="container-flex">
                                    <div class="destaque-texto">
                                        <span class="badge-tag"><?= htmlspecialchars($destaque["badge_text"]) ?></span>

                                        <h2><?= htmlspecialchars($destaque["title"]) ?></h2>

                                        <p><?= htmlspecialchars($destaque["description"]) ?></p>

                                        <div class="preco-box">
                                            <span class="preco-atual"><?= htmlspecialchars(formatarPrecoBr($destaque["price"])) ?></span>

                                            <?php if (!empty($destaque["combo_price"])): ?>
                                                <span class="Preco-Combo"><?= htmlspecialchars(formatarPrecoBr($destaque["combo_price"])) ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <a class="btn-comprar">Adicionar ao Pedido</a>
                                    </div>

                                    <div class="destaque-img1">
                                        <div class="img-placeholder">
                                            <img src="<?= htmlspecialchars($destaque["image_path"]) ?>" alt="<?= htmlspecialchars($destaque["image_alt"]) ?>">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <form method="POST" enctype="multipart/form-data" class="form-destaque-admin">
                            <input type="hidden" name="acao" value="editar_destaque">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($destaque["id"]) ?>">

                            <div class="campo-admin pequeno">
                                <label>ID HTML</label>
                                <input type="text" name="html_id" value="<?= htmlspecialchars($destaque["html_id"]) ?>" required>
                            </div>

                            <div class="campo-admin pequeno">
                                <label>Chave do produto</label>
                                <input type="text" name="product_key" value="<?= htmlspecialchars($destaque["product_key"]) ?>" required>
                            </div>

                            <div class="campo-admin">
                                <label>Badge</label>
                                <input type="text" name="badge_text" value="<?= htmlspecialchars($destaque["badge_text"]) ?>" required>
                            </div>

                            <div class="campo-admin">
                                <label>Título</label>
                                <input type="text" name="title" value="<?= htmlspecialchars($destaque["title"]) ?>" required>
                            </div>

                            <div class="campo-admin campo-full">
                                <label>Descrição</label>
                                <textarea name="description" rows="4" required><?= htmlspecialchars($destaque["description"]) ?></textarea>
                            </div>

                            <div class="campo-admin pequeno">
                                <label>Preço</label>
                                <input type="text" name="price" value="<?= htmlspecialchars($destaque["price"]) ?>" required>
                            </div>

                            <div class="campo-admin pequeno">
                                <label>Preço combo</label>
                                <input type="text" name="combo_price" value="<?= htmlspecialchars($destaque["combo_price"] ?? "") ?>">
                            </div>

                            <div class="campo-admin campo-full">
                                <label>Caminho da imagem</label>
                                <input type="text" name="image_path" value="<?= htmlspecialchars($destaque["image_path"]) ?>" required>
                            </div>

                            <div class="campo-admin campo-full">
                                <label>Enviar nova imagem</label>
                                <input type="file" name="image_file" accept="image/png,image/jpeg,image/webp">
                            </div>

                            <div class="campo-admin campo-full">
                                <label>Texto alternativo da imagem</label>
                                <input type="text" name="image_alt" value="<?= htmlspecialchars($destaque["image_alt"]) ?>">
                            </div>

                            <div class="campo-admin pequeno">
                                <label>Ordem</label>
                                <input type="number" name="display_order" value="<?= htmlspecialchars($destaque["display_order"]) ?>">
                            </div>

                            <div class="checks-destaque campo-full">
                                <label>
                                    <input type="checkbox" name="is_light" value="1" <?= (int) $destaque["is_light"] === 1 ? "checked" : "" ?>>
                                    Fundo claro
                                </label>

                                <label>
                                    <input type="checkbox" name="is_reverse" value="1" <?= (int) $destaque["is_reverse"] === 1 ? "checked" : "" ?>>
                                    Imagem à esquerda
                                </label>

                                <label>
                                    <input type="checkbox" name="is_active" value="1" <?= (int) $destaque["is_active"] === 1 ? "checked" : "" ?>>
                                    Ativo
                                </label>
                            </div>

                            <button type="submit" class="btn-salvar-destaque">
                                💾 Salvar destaque
                            </button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </section>

        </div>
    </section>
</main>

<?php require "includes/footer.php"; ?>