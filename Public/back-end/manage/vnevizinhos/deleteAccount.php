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

$nickname = $_POST['nickname'];

$sql_get_id = "SELECT id FROM usuarios WHERE nickname = :nickname";
$stmt_get_id = $conn->prepare($sql_get_id);
$stmt_get_id->bindParam(':nickname', $nickname);
$stmt_get_id->execute();
$id_user = $stmt_get_id->fetchColumn();

$sql_delete_comments = "DELETE FROM comentarios WHERE id_user = :id_user";
$stmt_delete_comments = $conn->prepare($sql_delete_comments);
$stmt_delete_comments->bindParam(':id_user', $id_user);
$stmt_delete_comments->execute();

$sql_delete_user = "DELETE FROM usuarios WHERE nickname = :nickname";
$stmt_delete_user = $conn->prepare($sql_delete_user);
$stmt_delete_user->bindParam(':nickname', $nickname);
$stmt_delete_user->execute();


?>

