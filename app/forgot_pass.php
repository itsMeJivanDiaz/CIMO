<?php
use Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: http://localhost:8000');
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
if(isset($_POST['verification'])){

    function random_strings($length_of_string) { 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result),  0, $length_of_string); 
    } 
    function random_ID($prefix){
        return uniqid($prefix) . random_strings(10);
    }

    $veri = $_POST['verification'];
    $new_pass = $_POST['new-pass-login'];
    $tokenid = $veri[0] . random_ID('IDtkn=');
    $stmt = mysqli_stmt_init($conn);
    $sql = "SELECT * FROM account WHERE TokenID = ?;";

    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo json_encode(array(
            'status' => 'Error',
            'message' => 'Something went wrong',
        ));
    }else{
        mysqli_stmt_bind_param($stmt, 's', $veri);
        if(!mysqli_stmt_execute($stmt)){
            echo json_encode(array(
                'status' => 'Error',
                'message' => 'Something went wrong',
            ));
        }else{
            $res = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($res);
            if($row <= 0){
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'Sorry but your token is not valid',
                ));
            }else{
                $accountid = $row['AccountID'];
                $update = "UPDATE account SET Password = ? WHERE AccountID = ?;";
                if(!mysqli_stmt_prepare($stmt, $update)){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Something went wrong',
                    ));
                }else{
                    $hasspass = password_hash($new_pass, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, 'ss', $hasspass, $accountid);
                    if(!mysqli_stmt_execute($stmt)){
                        echo json_encode(array(
                            'status' => 'Error',
                            'message' => 'Something went wrong',
                        ));
                    }else{
                        $update_tokenid = "UPDATE authentication_token SET TokenID = ? WHERE TokenID = ?;";
                        mysqli_stmt_prepare($stmt, $update_tokenid);
                        mysqli_stmt_bind_param($stmt, 'ss', $tokenid, $veri);
                        mysqli_stmt_execute($stmt);
                        $update_tokenid_acc = "UPDATE account SET TokenID = ? WHERE AccountID = ?;";
                        mysqli_stmt_prepare($stmt, $update_tokenid_acc);
                        mysqli_stmt_bind_param($stmt, 'ss', $tokenid, $accountid);
                        mysqli_stmt_execute($stmt);
                        echo json_encode(array(
                            'status' => 'Success',
                            'message' => 'Reset password succesful.',
                        ));
                    }
                }
            }
        }
    }
}
?>