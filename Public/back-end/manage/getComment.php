<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

error_log("Requisição recebida: " . file_get_contents('php://input'));
error_log("Método recebido: " . $_SERVER['REQUEST_METHOD']);

require_once 'conectionDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars(trim($_POST['titleInput']), ENT_QUOTES, 'UTF-8');
    $comment = htmlspecialchars(trim($_POST['commentInput']), ENT_QUOTES, 'UTF-8');
    $nickname = htmlspecialchars(trim($_POST['nickname']), ENT_QUOTES, 'UTF-8');

    $sqlGetUserId = "SELECT id FROM usuarios WHERE nickname = :nickname";
    $stmtGetUserId = $conn->prepare($sqlGetUserId);
    $stmtGetUserId->bindParam(':nickname', $nickname);
    $stmtGetUserId->execute();
    $userId = $stmtGetUserId->fetchColumn();

    $sql = "INSERT INTO comentarios (title, comment, nickname, id_user) VALUES (:title, :comment, :nickname, :id_user)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':nickname', $nickname);
    $stmt->bindParam(':id_user', $userId);
    $stmt->execute();
}
/*$title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
$comment = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
$nickname = htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8');
$arquivo = '../Jsons/jsonGetNewComment.json';

if (file_exists($arquivo)) {
    $jsonAtual = file_get_contents($arquivo);
    $dadosExistentes = json_decode($jsonAtual, true);

    $dadosExistentes = [];

    $dadosExistentes[] = [
        'title' => $title,
        'comment' => $comment,
        'nickname' => $nickname
    ];

    $jsonParaSalvar = json_encode($dadosExistentes, JSON_PRETTY_PRINT);

    file_put_contents($arquivo, $jsonParaSalvar);
}*/

//header('Location: http://localhost:8000/manage/sendComment.php');

?>