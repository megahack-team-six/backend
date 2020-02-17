<?php
include_once '../config/header_post.php';
// $rules = array('usuario.desativar');
// include_once '../config/validate.php';
include_once '../config/database.php';
include_once '../objects/users.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && !empty($data->id)) {
    $user->id = $data->id;
    if ($user->desativar()){
        http_response_code(200);
        echo json_encode(array("message" => "Usuário foi desativado.", "id" => $data->id));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Não foi possível desativar usuário. Tente novamente!"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Forneça todas as informações."));
}
?>