<?php
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
class ValidateToken {
    private $jwt;
    public $data;
    private $key;
    public function __construct($key){
        $this->key = $key;
        $this->getHeader();
    }
    public function getHeader() {
        $headers = apache_request_headers();
        if(isset($headers['Authorization'])){
            $matches = array();
            if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
                $this->jwt = $matches[1];
                $this->getToken();
            }
        } else {
            http_response_code(401);
            die(json_encode(array(
                "message" => "Accesso negado.",
                "error" => 'Informe o token'
            )));
        }
    }
    public function getToken() {
        try {
            $this->data = JWT::decode($this->jwt, $this->key, array('HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            die(json_encode(array(
                "message" => "Accesso negado.",
                "error" => $e->getMessage()
            )));
        }
    }
}
