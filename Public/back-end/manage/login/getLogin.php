<?php

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: content-Type, Content-Type");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

error_log("Requisição recebida: " . file_get_contents('php://input'));
error_log("Método recebido: " . $_SERVER['REQUEST_METHOD']);

require_once '../DB/conectionDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (trim($_POST["nickname"]) != "" && trim($_POST["password"]) != "") {

        $nickname = htmlspecialchars($_POST["nickname"], ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');

        $sql = "SELECT * FROM usuarios WHERE nickname = :nickname AND password = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                "login" => "true",
                "messageError" => "",
                "nickname" => $nickname
            ]);
            exit;
        } else {
            echo json_encode([
                "login" => "false",
                "messageError" => "Nickname ou senha inválido"
            ]);
            exit;
        }
    }
    http_response_code(200);
} else {
    http_response_code(405);
}
