<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
function decoder($token, $secret_key){
    $jwt = JWT::decode($token, $secret_key, array('HS256'));
    if(!$jwt){
        return false;
    }else{
        $jwt_values = json_decode(json_encode($jwt), true);
        if($jwt_values['iat'] >= $jwt_values['exp']){
            return false;
        }else{
            return $jwt;
        }
    }
}
?>