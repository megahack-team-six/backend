<?php
include_once '../config/header_get.php';
$rules = array('usuario.ver', 'usuario.editar');
include_once '../config/validate.php';
include_once '../config/database.php';
include_once '../objects/perfil.php';

$database = new Database();
$db = $database->getConnection();
$perfil = new Perfil($db);
$list = $perfil->getTodosPerfil();

if (count($list)>0) {
    http_response_code(200);
    $array = array();
    $array['records'] = $list;
    echo json_encode($array);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Nenhum usuário encontrado.")
    );
}
?>