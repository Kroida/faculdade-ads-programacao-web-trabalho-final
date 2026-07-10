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