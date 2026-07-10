<?php

// Processa a atualização dos dados do próprio usuário.

require "../config/conexao.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Impede acesso direto; atualização só acontece por envio do formulário.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../processaUpdate.php");
    exit;
}

$id = $_SESSION["id"];

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$rawPassword = $_POST["password"] ?? "";

if ($name === "" || $email === "") {
    $_SESSION["erro"] = "Preencha todos os os campos obrigatórios.";
    header("Location: ../dadosCliente.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["erro"] = "E-mail inválido.";
    header("Location: ../dadosCliente.php");
    exit;
}

$profileImage = null;

// Upload de foto é opcional. Quando enviado, valida tamanho, tipo e destino.
if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] === UPLOAD_ERR_OK) {

    if ($_FILES["photo"]["size"] > 5 * 1024 * 1024) {

        $_SESSION["erro"] = "A imagem deve possuir no máximo 5 MB.";
        header("Location: ../dadosCliente.php");
        exit;
    }

    $mime = finfo_file(
        finfo_open(FILEINFO_MIME_TYPE),
        $_FILES["photo"]["tmp_name"]
    );

    $permitidos = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    if (!array_key_exists($mime, $permitidos)) {

        $_SESSION["erro"] = "Formato de imagem inválido.";
        header("Location: ../dadosCliente.php");
        exit;
    }

    $extensao = $permitidos[$mime];

    $novoNome = $id . "." . $extensao;

    $diretorio = "../uploads/profiles/";

    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    $destino = $diretorio . $novoNome;

    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $destino)) {

        $_SESSION["erro"] = "Erro ao salvar a imagem.";
        header("Location: ../dadosCliente.php");
        exit;
    }

    $profileImage = "uploads/profiles/" . $novoNome;
}

// A query é montada dinamicamente para atualizar senha e foto apenas se existirem.
$sql = "

UPDATE users

SET

name = :name,
email = :email

";

$params = [

    ":name" => $name,
    ":email" => $email,
    ":id" => $id

];

// Só troca a senha quando o usuário preenche o campo "Nova senha".
if ($rawPassword !== "") {

    $sql .= ", password_hash = :password";

    $params[":password"] = password_hash(
        $rawPassword,
        PASSWORD_DEFAULT
    );
}

// Só troca a imagem no banco quando um novo arquivo foi salvo com sucesso.
if ($profileImage !== null) {

    $sql .= ", profile_image = :photo";

    $params[":photo"] = $profileImage;
}

$sql .= "

WHERE id = :id

";

$stmt = $pdo->prepare($sql);

try {

    $stmt->execute($params);

    // Mantém a sessão sincronizada com os dados recém-salvos.
    $_SESSION["name"] = $name;
    $_SESSION["email"] = $email;

    // Só troca a imagem no banco quando um novo arquivo foi salvo com sucesso.
if ($profileImage !== null) {
        $_SESSION["profile_image"] = $profileImage;
    }

    $_SESSION["sucesso"] = "Dados atualizados com sucesso!";

    header("Location: ../dadosCliente.php");
    exit;

} catch (PDOException $e) {

    if ($e->getCode() === "23000") {

        $_SESSION["erro"] = "Este e-mail já está sendo utilizado.";
    } else {

        $_SESSION["erro"] = "Erro ao atualizar os dados.";
    }

    header("Location: ../dadosCliente.php");
    exit;
}