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

if ( 
    isset($data->id) && !empty($data->id) &&    
    isset($data->descricao) && !empty($data->descricao) &&    
    isset($data->servicos) && count($data->servicos) > 0
) {
    
    $perfil->id = $data->id;
    $perfil->descricao = $data->descricao;
    $perfil->servicos = json_encode($data->servicos);
    if($perfil->update()){
        http_response_code(200);
        echo json_encode(array("message" => "O perfil foi alterado.", "id" => $data->id));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Não foi possível alterar perfil."));
    }
} else {
    http_response_code(400);
    $message = "Forneça todas as informações.";
    if( isset($data->servicos) && count($data->servicos) == 0) {
        $message = "Forneça pelo menos um serviço.";
    }
    echo json_encode(array("message" => $message));
}
?>