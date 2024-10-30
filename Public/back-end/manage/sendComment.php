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

$arquivo = '../Jsons/jsonGetNewComment.json';

if (file_exists($arquivo)) {
    $jsonAtual = file_get_contents($arquivo);
    $dadosExistentes = json_decode($jsonAtual, true);

    $title = $dadosExistentes[0]["title"];
    $comment = $dadosExistentes[0]["comment"];
    $nickname = $dadosExistentes[0]["nickname"];
    
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


$sql = "SELECT title, comment, nickname FROM comentarios ORDER BY id DESC";
$stmt = $conn->prepare($sql);
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

$arquivoComentarios = '../Jsons/comentarios.json';
if (file_exists($arquivoComentarios)) {
    $jsonExistente = file_get_contents($arquivoComentarios);
    $dadosExistentes = json_decode($jsonExistente, true);

    $dadosExistentes = [];

    $dadosExistentes[] = $comentariosToJson;

    $jsonComentarios = json_encode($dadosExistentes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    file_put_contents($arquivoComentarios, $jsonComentarios);
}

header('Location: http://localhost:8000/html/vnevizinhos.html');




