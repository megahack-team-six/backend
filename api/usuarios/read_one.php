<?php
include_once '../config/header_get.php';
// $rules = array("senha.editar", "usuario.editar");
// include_once '../config/validate.php';
include_once '../config/database.php';
include_once '../objects/users.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$user->id = isset($_GET['id']) ? $_GET['id'] : die();
$user->readOne();

if ($user->email!=null) {
    http_response_code(200);
    echo json_encode($user);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "O usuário não existe."));
}
?>