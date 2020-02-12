<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
if($_SERVER['REQUEST_METHOD']=='OPTIONS') {
    die;
}

include_once '../config/database.php';
include_once '../objects/users.php';
include_once '../objects/perfil.php';
include_once '../config/core.php';
include_once '../shared/utilities.php';

$utilisties = new Utilities();

$database = new Database();
$db = $database->getConnection();
$user = new User($db);
$perfil = new Perfil($db);

$data = json_decode(file_get_contents("php://input"));
 
if (isset($data->email)) {

    $user->email = $data->email;
    $email_exists = $user->emailExists();
    $nome = $user->nome;

    if($email_exists && $user->estado == 'ativo'){

        $novaSenha = $utilisties->randomPassword();

        $user->password = $novaSenha;
        $user->alterar_senha = 1;

        $subject = "Sua nova senha";
        $body =  "";
        $body .=  "Olá, ".$nome."<br>";
        $body .=  "Seu endereço de e-mail é: ".$data->email."<br>";
        $body .= "Sua nova Senha é: ".$novaSenha."<br><br><br>";
        $body .= "Após entrar, você pode alterar sua senha no painel, basta você clicar em <b>USUÁRIO</b> e em seguida na opção <b>Mudar Senha</b> <br>";

        if ($user->updatesenha()) {
            $enviarEmail = $utilisties->emailSicredi($data->email, $subject, $body);
           if ($enviarEmail) {
               http_response_code(200);
               echo json_encode(array("message" => "Nova Senha enviada para seu E-mail."));
           } else {
               http_response_code(400);
               echo json_encode(array("message" => "Não foi possível alterar usuário."));
           }
        }
    } else {
        http_response_code(400);
        $message = "Não foi possível realizar login.";
        if (!$email_exists) {
            $message = "O email não existe.";
        } else if($user->estado != 'ativo') {
            $message = "O usuário se encontra desativado.";
        }
        echo json_encode(array("message" => $message));
    }
} else {
    http_response_code(404);
    $message = "Não foi possível realizar a operação.";
    echo json_encode(array("message" => $message));
}
?>