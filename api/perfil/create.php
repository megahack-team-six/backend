<?php
include_once '../config/header_post.php';
$rules = array('super');
include_once '../config/validate.php';
include_once '../config/database.php';
include_once '../objects/perfil.php';

$database = new Database();
$db = $database->getConnection();
$perfil = new Perfil($db);
$data = json_decode(file_get_contents("php://input"));

if (isset($data->descricao) && !empty($data->descricao)) {
    $perfil->descricao = $data->descricao;
    if ($id = $perfil->create()) {
        http_response_code(200);
        echo json_encode(array("message" => "O perfil foi criado.", "id" => $id));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Não foi possível criar perfil."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Forneça todas as informações."));
}
?>