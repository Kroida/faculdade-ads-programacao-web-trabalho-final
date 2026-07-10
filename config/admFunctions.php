<?php

require_once __DIR__ . "/conexao.php";

/**
 * Valida ID numérico.
 */
function validarId($id): int
{
    $id = filter_var($id, FILTER_VALIDATE_INT);

    return $id && $id > 0 ? $id : 0;
}

/**
 * Lista todos os usuários ativos, ignorando contas deletadas.
 */
function listUsers(PDO $pdo): array
{
    $sql = "
        SELECT 
            id,
            name,
            email,
            status,
            created_at
        FROM users
        WHERE deleted_at IS NULL
        ORDER BY id ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Busca um usuário pelo ID.
 */
function listUser(PDO $pdo, $id): ?array
{
    $id = validarId($id);

    if (!$id) {
        return null;
    }

    $sql = "
        SELECT 
            id,
            name,
            email,
            profile_image,
            status,
            created_at,
            updated_at
        FROM users
        WHERE id = :id
        AND deleted_at IS NULL
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id" => $id
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    return $usuario ?: null;
}

/**
 * Verifica se já existe outro usuário com o mesmo e-mail.
 */
function emailExiste(PDO $pdo, string $email, ?int $idIgnorado = null): bool
{
    $sql = "
        SELECT id
        FROM users
        WHERE email = :email
        AND deleted_at IS NULL
    ";

    $params = [
        ":email" => $email
    ];

    if ($idIgnorado !== null) {
        $sql .= " AND id != :id";
        $params[":id"] = $idIgnorado;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Insere um novo usuário.
 */
function insertUser(PDO $pdo): array
{
    if (!isset($_POST["cadastrar"])) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => []
        ];
    }

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $erros = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Preencha o e-mail corretamente.";
    }

    if (!empty($email) && emailExiste($pdo, $email)) {
        $erros[] = "Usuário não inserido. E-mail já cadastrado.";
    }

    if (!empty($erros)) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => $erros
        ];
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "
        INSERT INTO users (
            name,
            email,
            password_hash,
            profile_image,
            status
        ) VALUES (
            :name,
            :email,
            :password_hash,
            'uploads/profiles/default.png',
            'active'
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":name" => $name,
        ":email" => $email,
        ":password_hash" => $passwordHash
    ]);

    return [
        "ok" => true,
        "mensagem" => "Usuário cadastrado com sucesso!",
        "erros" => []
    ];
}

/**
 * Atualiza um usuário existente.
 */
function updateUser(PDO $pdo, $id): array
{
    $id = validarId($id);

    if (!$id) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => ["ID inválido."]
        ];
    }

    if ($id === 1) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => ["O administrador principal não pode ser editado."]
        ];
    }

    if (!isset($_POST["atualizar"])) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => ["Ação inválida."]
        ];
    }

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $status = $_POST["status"] ?? null;

    $erros = [];

    if (empty($email)) {
        $erros[] = "Preencha o e-mail corretamente.";
    }

    if (!empty($email) && emailExiste($pdo, $email, $id)) {
        $erros[] = "Usuário não atualizado. E-mail já cadastrado.";
    }

    $statusPermitidos = ["active", "inactive", "banned"];

    if ($status !== null && !in_array($status, $statusPermitidos, true)) {
        $erros[] = "Status inválido.";
    }

    if (!empty($erros)) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => $erros
        ];
    }

    $campos = [
        "name = :name",
        "email = :email"
    ];

    $params = [
        ":id" => $id,
        ":name" => $name,
        ":email" => $email
    ];

    if (!empty($password)) {
        $campos[] = "password_hash = :password_hash";
        $params[":password_hash"] = password_hash($password, PASSWORD_DEFAULT);
    }

    if ($status !== null) {
        $campos[] = "status = :status";
        $params[":status"] = $status;
    }

    $sql = "
        UPDATE users
        SET " . implode(", ", $campos) . "
        WHERE id = :id
        AND deleted_at IS NULL
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return [
        "ok" => true,
        "mensagem" => "Usuário atualizado com sucesso!",
        "erros" => []
    ];
}

/**
 * Deleta usuário usando soft delete.
 * Não apaga do banco; apenas marca como deletado.
 */
function deleteUser(PDO $pdo, $id): array
{
    $id = validarId($id);

    if (!$id) {
        return [
            "ok" => false,
            "mensagem" => "ID inválido."
        ];
    }

    if ($id === 1) {
        return [
            "ok" => false,
            "mensagem" => "O administrador principal não pode ser deletado."
        ];
    }

    $sql = "
    DELETE FROM users
    WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":id" => $id
    ]);

    if ($stmt->rowCount() > 0) {
        return [
            "ok" => true,
            "mensagem" => "Usuário deletado com sucesso!"
        ];
    }

    return [
        "ok" => false,
        "mensagem" => "Usuário não encontrado ou já deletado."
    ];
}

/**
 * Lista os destaques/produtos da página inicial.
 */
function listDestaques(PDO $pdo, bool $somenteAtivos = false): array
{
    $sql = "
        SELECT
            id,
            html_id,
            product_key,
            badge_text,
            title,
            description,
            price,
            combo_price,
            image_path,
            image_alt,
            is_light,
            is_reverse,
            is_active,
            display_order
        FROM featured_sections
    ";

    if ($somenteAtivos) {
        $sql .= " WHERE is_active = 1";
    }

    $sql .= " ORDER BY display_order ASC, id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Formata preço para exibição em reais.
 */
function formatarPrecoBr($valor): string
{
    return "R$ " . number_format((float) $valor, 2, ",", ".");
}

/**
 * Classe CSS usada para renderizar uma section-destaque.
 */
function classesDestaque(array $destaque): string
{
    $classes = ["section-destaque"];

    if ((int) $destaque["is_light"] === 1) {
        $classes[] = "bg-light";
    }

    if ((int) $destaque["is_reverse"] === 1) {
        $classes[] = "reverse";
    }

    return implode(" ", $classes);
}

/**
 * Faz upload opcional da imagem do destaque.
 */
function uploadImagemDestaque(int $id): array
{
    if (!isset($_FILES["image_file"]) || $_FILES["image_file"]["error"] === UPLOAD_ERR_NO_FILE) {
        return [
            "ok" => true,
            "path" => null,
            "erro" => ""
        ];
    }

    if ($_FILES["image_file"]["error"] !== UPLOAD_ERR_OK) {
        return [
            "ok" => false,
            "path" => null,
            "erro" => "Erro ao enviar a imagem."
        ];
    }

    $tmpName = $_FILES["image_file"]["tmp_name"];
    $mime = mime_content_type($tmpName);

    $extensoesPermitidas = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"
    ];

    if (!isset($extensoesPermitidas[$mime])) {
        return [
            "ok" => false,
            "path" => null,
            "erro" => "Imagem inválida. Use JPG, PNG ou WEBP."
        ];
    }

    $pastaDestino = __DIR__ . "/../uploads/destaques";

    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0777, true);
    }

    $extensao = $extensoesPermitidas[$mime];
    $nomeArquivo = "destaque_" . $id . "_" . time() . "." . $extensao;
    $destinoFinal = $pastaDestino . "/" . $nomeArquivo;

    if (!move_uploaded_file($tmpName, $destinoFinal)) {
        return [
            "ok" => false,
            "path" => null,
            "erro" => "Não foi possível salvar a imagem."
        ];
    }

    return [
        "ok" => true,
        "path" => "uploads/destaques/" . $nomeArquivo,
        "erro" => ""
    ];
}

/**
 * Atualiza um destaque/produto exibido na página inicial.
 */
function updateDestaque(PDO $pdo, $id): array
{
    $id = validarId($id);

    if (!$id) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => ["ID do destaque inválido."]
        ];
    }

    $htmlId = trim($_POST["html_id"] ?? "");
    $productKey = trim($_POST["product_key"] ?? "");
    $badgeText = trim($_POST["badge_text"] ?? "");
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = str_replace(",", ".", trim($_POST["price"] ?? ""));
    $comboPrice = str_replace(",", ".", trim($_POST["combo_price"] ?? ""));
    $imagePath = trim($_POST["image_path"] ?? "");
    $imageAlt = trim($_POST["image_alt"] ?? "");
    $displayOrder = filter_var($_POST["display_order"] ?? 0, FILTER_VALIDATE_INT);
    $isLight = isset($_POST["is_light"]) ? 1 : 0;
    $isReverse = isset($_POST["is_reverse"]) ? 1 : 0;
    $isActive = isset($_POST["is_active"]) ? 1 : 0;

    $erros = [];

    if ($htmlId === "") {
        $erros[] = "Informe o ID HTML da seção.";
    }

    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $htmlId)) {
        $erros[] = "O ID HTML deve conter apenas letras, números, hífen ou underline.";
    }

    if ($productKey === "") {
        $erros[] = "Informe a chave do produto.";
    }

    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $productKey)) {
        $erros[] = "A chave do produto deve conter apenas letras, números, hífen ou underline.";
    }

    if (strlen($title) < 2) {
        $erros[] = "Informe um título válido.";
    }

    if (strlen($description) < 10) {
        $erros[] = "A descrição precisa ter pelo menos 10 caracteres.";
    }

    if (!is_numeric($price) || (float) $price <= 0) {
        $erros[] = "Informe um preço válido.";
    }

    if ($comboPrice !== "" && (!is_numeric($comboPrice) || (float) $comboPrice <= 0)) {
        $erros[] = "Informe um preço de combo válido ou deixe em branco.";
    }

    if ($imagePath === "") {
        $erros[] = "Informe o caminho da imagem.";
    }

    if ($imageAlt === "") {
        $imageAlt = $title;
    }

    if ($displayOrder === false) {
        $displayOrder = 0;
    }

    $upload = uploadImagemDestaque($id);

    if (!$upload["ok"]) {
        $erros[] = $upload["erro"];
    }

    if ($upload["path"] !== null) {
        $imagePath = $upload["path"];
    }

    if (!empty($erros)) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => $erros
        ];
    }

    $sql = "
        UPDATE featured_sections
        SET
            html_id = :html_id,
            product_key = :product_key,
            badge_text = :badge_text,
            title = :title,
            description = :description,
            price = :price,
            combo_price = :combo_price,
            image_path = :image_path,
            image_alt = :image_alt,
            is_light = :is_light,
            is_reverse = :is_reverse,
            is_active = :is_active,
            display_order = :display_order
        WHERE id = :id
    ";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":id" => $id,
            ":html_id" => $htmlId,
            ":product_key" => $productKey,
            ":badge_text" => $badgeText,
            ":title" => $title,
            ":description" => $description,
            ":price" => (float) $price,
            ":combo_price" => $comboPrice === "" ? null : (float) $comboPrice,
            ":image_path" => $imagePath,
            ":image_alt" => $imageAlt,
            ":is_light" => $isLight,
            ":is_reverse" => $isReverse,
            ":is_active" => $isActive,
            ":display_order" => $displayOrder
        ]);
    } catch (PDOException $e) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => ["Erro ao atualizar destaque. Verifique se o ID HTML ou a chave do produto já não existem em outro item."]
        ];
    }

    return [
        "ok" => true,
        "mensagem" => "Destaque atualizado com sucesso!",
        "erros" => []
    ];
}

function insertDestaque(PDO $pdo): array
{
    $htmlId = trim($_POST["html_id"] ?? "");
    $productKey = trim($_POST["product_key"] ?? "");
    $badgeText = trim($_POST["badge_text"] ?? "");
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = str_replace(",", ".", trim($_POST["price"] ?? ""));
    $comboPrice = str_replace(",", ".", trim($_POST["combo_price"] ?? ""));
    $imagePath = trim($_POST["image_path"] ?? "");
    $imageAlt = trim($_POST["image_alt"] ?? "");
    $displayOrder = filter_var($_POST["display_order"] ?? 0, FILTER_VALIDATE_INT);

    $isLight = isset($_POST["is_light"]) ? 1 : 0;
    $isReverse = isset($_POST["is_reverse"]) ? 1 : 0;
    $isActive = isset($_POST["is_active"]) ? 1 : 0;

    $erros = [];

    $temUpload = isset($_FILES["image_file"])
        && $_FILES["image_file"]["error"] !== UPLOAD_ERR_NO_FILE;

    if ($htmlId === "") {
        $erros[] = "Informe o ID HTML da seção.";
    }

    if ($htmlId !== "" && !preg_match('/^[a-zA-Z0-9_-]+$/', $htmlId)) {
        $erros[] = "O ID HTML deve conter apenas letras, números, hífen ou underline.";
    }

    if ($productKey === "") {
        $erros[] = "Informe a chave do produto.";
    }

    if ($productKey !== "" && !preg_match('/^[a-zA-Z0-9_-]+$/', $productKey)) {
        $erros[] = "A chave do produto deve conter apenas letras, números, hífen ou underline.";
    }

    if ($badgeText === "") {
        $erros[] = "Informe o texto da badge.";
    }

    if (strlen($title) < 2) {
        $erros[] = "Informe um título válido.";
    }

    if (strlen($description) < 10) {
        $erros[] = "A descrição precisa ter pelo menos 10 caracteres.";
    }

    if (!is_numeric($price) || (float) $price <= 0) {
        $erros[] = "Informe um preço válido.";
    }

    if ($comboPrice !== "" && (!is_numeric($comboPrice) || (float) $comboPrice <= 0)) {
        $erros[] = "Informe um preço de combo válido ou deixe em branco.";
    }

    if ($imagePath === "" && !$temUpload) {
        $erros[] = "Informe o caminho da imagem ou envie uma nova imagem.";
    }

    if ($imageAlt === "") {
        $imageAlt = $title;
    }

    if ($displayOrder === false) {
        $displayOrder = 0;
    }

    if (!empty($erros)) {
        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => $erros
        ];
    }

    try {
        $pdo->beginTransaction();

        $sql = "
            INSERT INTO featured_sections (
                html_id,
                product_key,
                badge_text,
                title,
                description,
                price,
                combo_price,
                image_path,
                image_alt,
                is_light,
                is_reverse,
                is_active,
                display_order
            ) VALUES (
                :html_id,
                :product_key,
                :badge_text,
                :title,
                :description,
                :price,
                :combo_price,
                :image_path,
                :image_alt,
                :is_light,
                :is_reverse,
                :is_active,
                :display_order
            )
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":html_id" => $htmlId,
            ":product_key" => $productKey,
            ":badge_text" => $badgeText,
            ":title" => $title,
            ":description" => $description,
            ":price" => (float) $price,
            ":combo_price" => $comboPrice === "" ? null : (float) $comboPrice,
            ":image_path" => $imagePath !== "" ? $imagePath : "uploads/destaques/temporario.jpg",
            ":image_alt" => $imageAlt,
            ":is_light" => $isLight,
            ":is_reverse" => $isReverse,
            ":is_active" => $isActive,
            ":display_order" => $displayOrder
        ]);

        $novoId = (int) $pdo->lastInsertId();

        if ($temUpload) {
            $upload = uploadImagemDestaque($novoId);

            if (!$upload["ok"]) {
                $pdo->rollBack();

                return [
                    "ok" => false,
                    "mensagem" => "",
                    "erros" => [$upload["erro"]]
                ];
            }

            $imagePath = $upload["path"];

            $sqlImagem = "
                UPDATE featured_sections
                SET image_path = :image_path
                WHERE id = :id
            ";

            $stmtImagem = $pdo->prepare($sqlImagem);

            $stmtImagem->execute([
                ":image_path" => $imagePath,
                ":id" => $novoId
            ]);
        }

        $pdo->commit();

        return [
            "ok" => true,
            "mensagem" => "Destaque cadastrado com sucesso!",
            "erros" => []
        ];
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return [
            "ok" => false,
            "mensagem" => "",
            "erros" => [
                "Erro ao cadastrar destaque. Verifique se o ID HTML ou a chave do produto já existem."
            ]
        ];
    }
}