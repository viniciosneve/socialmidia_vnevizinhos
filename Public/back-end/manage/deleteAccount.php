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

$arquivo_userLogado = '../Jsons/getUserLoged.json';
if (file_exists($arquivo_userLogado)) {
    $jsonAtualUserLogado = file_get_contents($arquivo_userLogado);
    $dadosExistentesUserLogado = json_decode($jsonAtualUserLogado, true);

    $userLoged = $dadosExistentesUserLogado[0]['nickname'];

    $arquivo_users = '../Jsons/Users.json';
    if (file_exists($arquivo_users)) {
        $jsonAtualUsers = file_get_contents($arquivo_users);
        $dadosExistentesUsers = json_decode($jsonAtualUsers, true);

        foreach ($dadosExistentesUsers['Users'] as $user => $values) {
            if ($userLoged == $dadosExistentesUsers['Users'][$user]['nickname']) {
                unset($dadosExistentesUsers['Users'][$user]);
                $jsonUserExistentes = json_encode($dadosExistentesUsers, JSON_PRETTY_PRINT);
                file_put_contents($arquivo_users, $jsonUserExistentes);

                $sql_get_id = "SELECT id FROM usuarios WHERE nickname = :nickname";
                $stmt_get_id = $conn->prepare($sql_get_id);
                $stmt_get_id->bindParam(':nickname', $userLoged);
                $stmt_get_id->execute();
                $id_user = $stmt_get_id->fetchColumn();
                var_dump($id_user);

                $sql_delete_comments = "DELETE FROM comentarios WHERE id_user = :id_user";
                $stmt_delete_comments = $conn->prepare($sql_delete_comments);
                $stmt_delete_comments->bindParam(':id_user', $id_user);
                $stmt_delete_comments->execute();

                $sql_delete_user = "DELETE FROM usuarios WHERE nickname = :nickname";
                $stmt_delete_user = $conn->prepare($sql_delete_user);
                $stmt_delete_user->bindParam(':nickname', $userLoged);
                $stmt_delete_user->execute();

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

                header('Location: ../html/login.html');
            }
        }
    }
}


?>

