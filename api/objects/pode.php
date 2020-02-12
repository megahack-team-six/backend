<?php
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
class Pode {
    private $jwt;
    public $user;
    private $rules;
    public function __construct($user, $rules) {
        $this->user = $user->data;
        $this->rules = $rules;
        $this->pode();
    }
    function pode() {
        $pode = false;
        foreach ($this->rules as $key => $value) {
            if (in_array($value, $this->user->rules)) $pode = true;
        }
        if (!$pode && !in_array('super', $this->user->rules) && !in_array('qualquerUsuarioLogado', $this->rules)) {
            http_response_code(403);
            die(json_encode(array(
                "message" => "Accesso negado.",
                "error" => 'Informe o token'
            )));
        }
    }
}
?>
