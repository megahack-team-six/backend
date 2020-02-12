<?php
include_once '../config/header_post.php';
$rules = array('usuario.criar');
include_once '../config/validate.php';
include_once '../config/database.php';
include_once '../objects/users.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$data = json_decode(file_get_contents("php://input"));

if (
    isset($data->nome) && !empty($data->nome) &&    
    isset($data->email) && !empty($data->email) &&    
    isset($data->password) && !empty($data->password) &&    
    isset($data->repetPassword) && !empty($data->repetPassword)
) {
    $user->nome = $data->nome;
    $user->email = $data->email;
    $user->password = $data->password;
    $user->repetPassword = $data->repetPassword;

    if ($data->password != $data->repetPassword) {
        http_response_code(400);
        echo json_encode(array("message" => "As senhas não conferem."));
    } else if ($id = $user->create()) {
        http_response_code(200);
        echo json_encode(array("message" => "Usuário criado.", "id" => $id));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Não foi possível criar usuário."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Forneça todas as informações."));
}
?>