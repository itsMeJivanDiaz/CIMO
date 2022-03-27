<?php
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
header('Content-Type:application/json');
if(isset($_GET['get_jwt'])){
    
    require 'db.php';

    $online = "online";

    $result_array = array();
        
    $token = $_GET['get_jwt'];
    $key = $_GET['get_key'];
    $new_id = $_GET['get_id'];

    $new_array = array(
        'jwt' => $token,
        'key' => $key,
        'status' => 'Success',
    );

    $sql = "UPDATE authentication_token SET TokenStatus = ? WHERE TokenID = ?;";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $online, $new_id);
    mysqli_stmt_execute($stmt);

    array_push($result_array, $new_array);

    echo json_encode($result_array, JSON_PRETTY_PRINT);

}
?>