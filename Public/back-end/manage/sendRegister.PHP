<?php
require_once 'conectionDB.php';


$arquivo = '../Jsons/jsonReciveCreateAcont.json';
if (file_exists($arquivo)) {
    $jsonAtual = file_get_contents($arquivo);
    $dadosExistentes = json_decode($jsonAtual, true);

    $name = $dadosExistentes[0]["name"];
    $lastname = $dadosExistentes[0]["lastname"];
    $birthdate = $dadosExistentes[0]["birthdate"];
    $nickname = $dadosExistentes[0]["nickname"];
    $password = $dadosExistentes[0]["password"];
    $user_on = $dadosExistentes[0]["user_on"];
    
    $user_on = ($user_on === 'true') ? 'true' : 'false';
    
    $sql = "INSERT INTO usuarios (name, lastname, nickname, birthdate, password, user_on) VALUES (:value_name, :value_lastname, :value_nickname, :value_birthdate, :value_password, :value_user_on)";

    $stmt = $conn->prepare($sql);

    $values = [
        ':value_name' => $name, 
        ':value_lastname' => $lastname, 
        ':value_nickname' => $nickname, 
        ':value_birthdate' => $birthdate,
        ':value_password' => $password, 
        ':value_user_on' => $user_on
    ];
    

    $stmt->execute($values);

    $arquivo_json_user = "../Jsons/Users.json";
    if (file_exists($arquivo_json_user)) {
        $jsonUserAtual = file_get_contents($arquivo_json_user);
        $dadosUsers = json_decode($jsonUserAtual, true);

        $valuesToJson = [
            'name' => $name, 
            'lastname' => $lastname, 
            'nickname' => $nickname, 
            'birthdate' => $birthdate, 
            'user_on' => $user_on
        ];

        $dadosUsers['Users'][] = $valuesToJson;
        
        $jsonUserParaSalvar = json_encode($dadosUsers, JSON_PRETTY_PRINT);
        file_put_contents($arquivo_json_user, $jsonUserParaSalvar);
    }

    
    
}

header("Location: http://localhost:8000/html/login.html");
