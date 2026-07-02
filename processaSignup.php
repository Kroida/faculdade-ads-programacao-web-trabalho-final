<?php

require "conexao.php";

$name = $_POST["name"];
$email = $_POST["email"];
$password = password_hash(
    $_POST["password"],
    PASSWORD_DEFAULT
);

$sql = "

INSERT INTO users

(name, email, password_hash)

VALUES

(:name, :email, :password)

";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ":name" => $name,
    ":email" => $email,
    ":password" => $password

]);

echo "Usuário cadastrado!";