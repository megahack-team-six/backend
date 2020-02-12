<?php
include_once '../config/header_get.php';
$rules = array('usuario');
include_once '../config/validate.php';
include_once '../config/database.php';
include_once '../objects/users.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$stmt = $user->listar();
$num = $stmt->rowCount();

if ($num>0) {
    $usuarios_arr=array();
    $usuarios_arr["records"]=array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        array_push($usuarios_arr["records"], $row);
    }
    http_response_code(200);
    echo json_encode($usuarios_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "Nenhum usuaário encontrado.")
    );
}
?>