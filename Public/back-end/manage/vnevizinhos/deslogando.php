<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}
error_log("Requisição recebida: " . file_get_contents('php://input'));
error_log("Método recebido: " . $_SERVER['REQUEST_METHOD']);

require_once '../DB/conectionDB.php';

try {
    $nickname = $_POST['nickname'];
    echo json_encode(['nickname' => $nickname]);
    $sql = "UPDATE usuarios SET user_on = false WHERE nickname = :nickname";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nickname', $nickname);
    $stmt->execute();

} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}



?>