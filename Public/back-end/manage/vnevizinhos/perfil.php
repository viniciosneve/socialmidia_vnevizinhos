<?php
header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    exit(0);
}

error_log('Requisição recebida:' . file_get_contents('php://input'));
error_log('Método recebido: ' . $_SERVER['REQUEST_METHOD']);

require_once '../DB/conectionDB.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nickname = $_POST['nickname'];

    $getInfoFromDbAboutUser = ('SELECT name, lastname, birthdate, user_on FROM usuarios WHERE nickname = :nickname');
    $stmtGetInfoFromDbAboutUser = $conn->prepare($getInfoFromDbAboutUser);
    $stmtGetInfoFromDbAboutUser->bindParam(':nickname', $nickname);
    $stmtGetInfoFromDbAboutUser->execute();

    $infoUser = $stmtGetInfoFromDbAboutUser->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["infoUser" => $infoUser]);
}
?>