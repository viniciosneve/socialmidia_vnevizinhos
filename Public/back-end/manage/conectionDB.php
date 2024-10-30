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


?>