<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    exit(0);
}
error_log("Requisição recebida: " . file_get_contents('php://input'));
error_log("Método recebido: " . $_SERVER['REQUEST_METHOD']);

require_once '../DB/conectionDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars(trim($_POST['titleInput']), ENT_QUOTES, 'UTF-8');
    $comment = htmlspecialchars(trim($_POST['commentInput']), ENT_QUOTES, 'UTF-8');
    $nickname = htmlspecialchars(trim($_POST['nickname']), ENT_QUOTES, 'UTF-8');

    $sqlGetUserId = "SELECT id FROM usuarios WHERE nickname = :nickname";
    $stmtGetUserId = $conn->prepare($sqlGetUserId);
    $stmtGetUserId->bindParam(':nickname', $nickname);
    $stmtGetUserId->execute();
    $userId = $stmtGetUserId->fetchColumn();

    $sql_add_comment = "INSERT INTO comentarios (title, comment, nickname, id_user) VALUES (:title, :comment, :nickname, :id_user)";
    $stmt = $conn->prepare($sql_add_comment);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':nickname', $nickname);
    $stmt->bindParam(':id_user', $userId);
    $stmt->execute();
}

?>