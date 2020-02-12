<?php
include_once '../config/header_post.php';
$rules = array('usuario.editar');
include_once '../config/validate.php';
include_once '../config/database.php';
include_once '../objects/users.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$data = json_decode(file_get_contents("php://input"));

if ( 
    isset($data->id) && !empty($data->id) &&    
    isset($data->nome) && !empty($data->nome) &&    
    isset($data->email) && !empty($data->email)
) {
    
    $user->email = $data->email;
    $email_exists = $user->emailExists();
    if ($email_exists && $user->id != $data->id) {
        http_response_code(400);
        echo json_encode(array("message" => "O email fornecido já está em uso.", "id" => $data->id));
        die;
    }
    $user->id = $data->id;
    $user->nome = $data->nome;
    $perfil = json_encode($data->perfil);
    $user->perfil = is_array($data->perfil) ? $perfil : json_encode(array($perfil));
    if ($user->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Usuário foi alterado.", "id" => $data->id));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Não foi possível alterar usuário. Tente novamente!"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Forneça todas as informações."));
}
?>