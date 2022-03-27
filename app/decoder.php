<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
if(isset($_POST['data'])){

    $info_array = $_POST['data'];
    $array_ = [];

    $token = $_POST['token'][0];
    $secret_key = $_POST['token'][1];

    $jwt = JWT::decode($token, $secret_key, array('HS256'));
    
    if(!$jwt){
        echo json_encode('Something Is Wrong With Your Token');
    }else{
        $jwt_values = json_decode(json_encode($jwt), true);
        if($jwt_values['iat'] >= $jwt_values['exp']){
            echo json_encode('Something Is Wrong With Your Token');
        }else{
            foreach($info_array as $info){
                array_push($array_, $jwt_values['data'][$info]);
            }
            echo json_encode($array_);
        }
    }
}
?>