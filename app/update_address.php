<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
if(isset($_POST['data'])){
    $street = $_POST['data'][1];
    echo json_encode($street['value']);
}
?>