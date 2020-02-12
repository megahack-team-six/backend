<?php
include_once '../config/header_get.php';
$rules = array('super');
include_once '../config/validate.php';
include_once '../config/database.php';
include_once '../objects/perfil.php';

$database = new Database();
$db = $database->getConnection();
$perfil = new Perfil($db);
$list = $perfil->getTodosServicos();

if (count($list)>0){
    
    http_response_code(200);
    $array = array();
    $array['records'] = array();

    foreach ($list as $key => $servico) {
        if($servico['acao'])
            $servico['descricao'] = ucwords ("${servico['acao']} ${servico['servico']}");
        else $servico['descricao']  = ucwords ($servico['servico']);
        array_push($array['records'], $servico);
    }
    echo json_encode($array);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Nenhum usuário encontrado.")
    );
}
?>