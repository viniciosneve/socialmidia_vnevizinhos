<?php
header("Access-Control-Allow-Origin: http://localhost:8080");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: content-Type", "Content-Type");

require_once "../DB/conectionDB.php";

$sql_get_comments = "SELECT title, comment, nickname FROM comentarios ORDER BY id DESC";
$stmt = $conn->prepare($sql_get_comments);
$stmt->execute();

$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$comentariosToJson = [];
foreach ($comentarios as $comentario) {
    $comentarioToJson = [
        'nickname' => $comentario['nickname'],
        'title' => $comentario['title'],
        'comment' => $comentario['comment']
    ];
    $comentariosToJson[] = $comentarioToJson;
}

echo json_encode([
    "comments" => $comentariosToJson
]);
exit;
?>