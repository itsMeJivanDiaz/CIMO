<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
if(isset($_POST['data'])){

    $zero = 0;
    $token = $_POST['token'][0];
    $key = $_POST['token'][1];
    
    $stmt = mysqli_stmt_init($conn);

    $jwt = decoder($token, $key);

    $status = "offline";

    $jwt_token = json_decode(json_encode($jwt), true);

    $token_id = $jwt_token['data']['tok_id'];

    $sql = "UPDATE authentication_token SET TokenStatus = ? WHERE TokenID = ?;";

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $status, $token_id);
    mysqli_stmt_execute($stmt);

    $count_id = $jwt_token['data']['cnt_id'];

    $sql_zero_count = "UPDATE count SET CurrentCount = ?, Counter = ? WHERE CountID = ?;";

    mysqli_stmt_prepare($stmt, $sql_zero_count);
    mysqli_stmt_bind_param($stmt, 'iis', $zero, $zero, $count_id);
    mysqli_stmt_execute($stmt);

    echo json_encode(array(
        'status' => 'Success'
    ));

}

?>