<?php
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
if(isset($_POST['token'])){
    
    if(isset($_POST['mobile']) == 'false'){

        $zero = 0;
        $status = "offline";
        $token = $_POST['token'];
        $key = $_POST['key'];

        $stmt = mysqli_stmt_init($conn);

        $jwt = decoder($token, $key);

        $jwt_token = json_decode(json_encode($jwt), true);

        $token_id = $jwt_token['data']['tok_id'];

        $sql = "UPDATE authentication_token SET TokenStatus = ? WHERE TokenID = ?;";

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $status, $token_id);
        mysqli_stmt_execute($stmt);

        $sql_uc = "UPDATE count SET CurrentCount = ?, Counter = ? WHERE CountID = ?;";

        $count_id = $jwt_token['data']['cnt_id'];

        mysqli_stmt_prepare($stmt, $sql_uc);
        mysqli_stmt_bind_param($stmt, 'iss', $zero, $zero, $count_id);
        mysqli_stmt_execute($stmt);

    }else if(isset($_POST['mobile']) == 'true'){
        $zero = 0;
        $status = "offline";
        $token = $_POST['token'];
        $key = $_POST['key'];

        $stmt = mysqli_stmt_init($conn);

        $jwt = decoder($token, $key);

        $jwt_token = json_decode(json_encode($jwt), true);

        $token_id = $jwt_token['data']['tok_id'];

        $sql = "UPDATE authentication_token SET TokenStatus = ? WHERE TokenID = ?;";

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 'ss', $status, $token_id);
        mysqli_stmt_execute($stmt);
    }
}
?>