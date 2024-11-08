<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

error_log("Requisição recebida: " . file_get_contents('php://input'));
error_log("Método recebido: " . $_SERVER['REQUEST_METHOD']);

require_once '../DB/conectionDB.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["nameUser"];
    $lastname = $_POST["lastname"];
    $birthdate = $_POST["birthdate"];
    $nickname = $_POST["nickname"];
    $password = $_POST["password"];
    $confirnPassword = $_POST["confirnPassword"];

    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
    $birthdate = htmlspecialchars($birthdate, ENT_QUOTES, 'UTF-8');
    $nickname = htmlspecialchars($nickname, ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
    $confirnPassword = htmlspecialchars($confirnPassword, ENT_QUOTES, 'UTF-8');

    $nicknameExiste = 'false';
    $passwordIgual = 'false';
    $dataisValida = 'false';

    $sqlVerificarNickname = "SELECT COUNT(*) FROM usuarios WHERE nickname = :nickname";
    $stmtVerificarNickname = $conn->prepare($sqlVerificarNickname);
    $stmtVerificarNickname->bindParam(':nickname', $nickname);
    $stmtVerificarNickname->execute();

    $dataisValida = 'false';
    $pattern = "/^\d{2}\/\d{2}\/\d{4}$/";
    if (preg_match($pattern, $birthdate)) {
        $dataisValida = 'true';
    } else {
        $dataisValida = 'false';
    }
    
    if ($stmtVerificarNickname->fetchColumn() > 0) {
        $nicknameExiste = 'true';
    } else {
        $nicknameExiste = 'false';
    }

    if ($password != $confirnPassword) {
        $passwordIgual = 'false';
    } else {
        $passwordIgual = 'true';
    }

    if ($nicknameExiste == 'false' && $passwordIgual == 'true' && $dataisValida == 'true') {
        $dados = [
            'name' => $name,
            'lastname' => $lastname,
            'birthdate' => $birthdate,
            'nickname' => $nickname,
            'password' => $password,
            'user_on' => 'true'
        ];

        $sql = "INSERT INTO usuarios (name, lastname, nickname, birthdate, password, user_on) VALUES (:name, :lastname, :nickname, :birthdate, :password, :user_on)";
        
        $stmt = $conn->prepare($sql);
        
        try {
            $stmt->execute($dados);
        } catch (PDOException $e) {
            echo json_encode(["sucesso" => false, "mensagem" => "Erro ao cadastrar usuário: " . $e->getMessage()]);
        }
    }

    echo json_encode([
        "messageNickname" => $nicknameExiste == 'true' ? "Nickname já existe" : "nickname disponível",
        "nicknameExiste" => $nicknameExiste,
        "messagePassword" => $passwordIgual == 'true' ? "Senhas iguais" : "As senhas não são iguais",
        "passwordIgual" => $passwordIgual,
        "dataisValida" => $dataisValida,
        "messageData" => $dataisValida == 'true' ? "Data válida" : "Data inválida"
    ]);
    exit;

    http_response_code(200);
} else {
    http_response_code(405);
}