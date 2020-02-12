<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
if ($_SERVER['REQUEST_METHOD']=='OPTIONS') {
    die;
}

include_once '../config/database.php';
include_once '../objects/users.php';
include_once '../objects/perfil.php';
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$perfil = new Perfil($db);
$data = json_decode(file_get_contents("php://input"));

if (isset($data->email)) {

    $user->email = $data->email;
    $email_exists = $user->emailExists();

    if ($email_exists && password_verify($data->password, $user->password) && $user->status) {
        $rules = $perfil->getRulesByUser($user->perfil);
        
        $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => time(),
        "exp" => time() + (60 * 60 * 24),
        "nbf" => $nbf,
        "data" => array(
            "id" => $user->id,
            "nome" => $user->nome,
            "email" => $user->email,
            "alterar_senha" => ($user->alterar_senha==0) ? false : true,
            "perfil" => $user->perfil,
            "rules" => $rules,
        ));

        http_response_code(200);
        $jwt = JWT::encode($token, $key);
        echo json_encode(
            array(
                "message" => "Login efetuado com sucesso",
                "jwt" => $jwt,
                "id" => $user->id,
                "nome" => $user->nome,
                "email" => $user->email,
                "alterar_senha" => ($user->alterar_senha==0) ? false : true,
                "perfil" => $user->perfil,
                "rules" => $rules,
            )
        );
    } else {
        http_response_code(400);
        $message = "Não foi possível realizar login.";
        if (!$email_exists) {
            $message = "O email não existe.";
        } else if(!password_verify($data->password, $user->password)) {
            $message = "A senha não confere.";
        } else if (!$user->status) {
            $message = "O usuário se encontra desativado.";
        }
        echo json_encode(array("message" => $message));
    }
} else {
    http_response_code(404);
    $message = "Não foi possível realizar login.";
    echo json_encode(array("message" => $message));
}
?>