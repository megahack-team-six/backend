<?php
include_once '../config/header_get.php';
$rules = array('super');
include_once '../config/validate.php';
include_once '../config/database.php';
include_once '../objects/perfil.php';

$database = new Database();
$db = $database->getConnection();
$perfil = new Perfil($db);
$perfil->id = isset($_GET['id']) ? $_GET['id'] : die();
$item = $perfil->readOne();

if ($item['descricao']!=null) {
    http_response_code(200);
    $item['servicos'] = $item['servicos'] ? json_decode($item['servicos']) : array();
        echo json_encode($item);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "O perfil não existe."));
}
?>