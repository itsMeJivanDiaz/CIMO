<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
function encoder($token_array, $secret_key){
    $jwt = JWT::encode($token_array, $secret_key);
    return $jwt;
}
?>