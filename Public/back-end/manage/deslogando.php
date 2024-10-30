<?php
require_once 'mypassword.php';

$dsn = "pgsql:host=localhost;port=5433;dbname=usuários;user=postgres;";
$usuario = 'postgres';
$senha = $password_SQL;

try {
    $conn = new PDO($dsn, $usuario, $senha);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}

$arquivo = '../Jsons/getUserLoged.json';
if (file_exists($arquivo)) {
    $jsonAtual = file_get_contents($arquivo);
    $dadosExistentes = json_decode($jsonAtual, true);

    $nickname = $dadosExistentes[0]["nickname"];

    $sql = "UPDATE usuarios SET user_on = false WHERE nickname = :nickname";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nickname', $nickname);
    $stmt->execute();

    $dadosExistentes[0]["nickname"] = '';
    $jsonAtual = json_encode($dadosExistentes);
    file_put_contents($arquivo, $jsonAtual);
}

header('Location: http://localhost:8000/html/login.html');


?>