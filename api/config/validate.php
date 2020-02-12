<?php

include_once '../objects/validate_token.php';
include_once '../objects/pode.php';
include_once '../config/core.php';

$validate = new ValidateToken($key);
$data = $validate->data;
$user = $data->data;
$pode = new Pode($data, $rules);
?>