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


$arquivo = '../Jsons/jsonReciveCreateAcont.json';
if (file_exists($arquivo)) {
    $jsonAtual = file_get_contents($arquivo);
    $dadosExistentes = json_decode($jsonAtual, true);

    $nickname = $dadosExistentes[0]["nickname"];
    $password = $dadosExistentes[0]["password"];
}


$sql = "SELECT * FROM usuarios";

$stmt = $conn->prepare($sql);
$stmt->execute();


$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (count($resultados) > 0) {
    foreach ($resultados as $row) {

        if ($row['nickname'] == $nickname && $row['password'] == $password){
            if (array_key_exists(1, $dadosExistentes)){
                unset($dadosExistentes['Alert']);
                $dadosExistentes = array_values($dadosExistentes);
                $jsonUserParaSalvar = json_encode($dadosExistentes, JSON_PRETTY_PRINT);
                file_put_contents($arquivo, $jsonUserParaSalvar);
                
            }
            
            $arquivo_users = '../Jsons/Users.json';
            if (file_exists($arquivo_users)) {
                $jsonAtualUsers = file_get_contents($arquivo_users);
                $dadosExistentesUsers = json_decode($jsonAtualUsers, true);

                foreach($dadosExistentesUsers['Users'] as $users => $values) {
                    if ($row['nickname'] == $dadosExistentesUsers['Users'][$users]['nickname']) {
                        
                        $dadosExistentesUsers['Users'][$users]["user_on"] = 'true';
                        $jsonUserExistentes = json_encode($dadosExistentesUsers, JSON_PRETTY_PRINT);
                        file_put_contents($arquivo_users, $jsonUserExistentes);

                        $sql = "UPDATE usuarios SET user_on = :valor1 WHERE nickname = :nickname";
                        $stmt = $conn->prepare($sql);
                        
                        $nickname = $row['nickname'];
                        $valor1 = "true";
                        
                        $stmt->bindParam(':nickname', $nickname, PDO::PARAM_STR);
                        $stmt->bindParam(':valor1', $valor1, PDO::PARAM_STR);
                        
                        $stmt->execute();
                    }

                }
            
            }

            $arquivo_user_loged = '../Jsons/getUserLoged.json';
            if (file_exists($arquivo_user_loged)) {
                $jsonAtualUserLoged = file_get_contents($arquivo_user_loged);
                $dadosExistentesUserLoged = json_decode($jsonAtualUserLoged, true);


                $dadosExistentesUserLoged = [];

                $dadosExistentesUserLoged[] = [
                    "nickname" => $nickname
                ];

                $jsonUserParaSalvar = json_encode($dadosExistentesUserLoged, JSON_PRETTY_PRINT);
                file_put_contents($arquivo_user_loged, $jsonUserParaSalvar);
            }
            
            header("Location: http://localhost:8000/html/login.html");
            exit();
            die();
        }


    }
}


$dadosExistentes["Alert"] = ["aviso" => "Login ou senha errada"];
$jsonUserParaSalvar = json_encode($dadosExistentes, JSON_PRETTY_PRINT);
file_put_contents($arquivo, $jsonUserParaSalvar);

header("Location: http://localhost:8000/html/login.html");

