<?php

use Firebase\JWT\JWT;

header('Access-Control-Allow-Origin: http://localhost:8000');
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
if(isset($_POST['data'])){
    
    $data_old = $_POST['data'][0];
    $data_new = $_POST['data'][1];
    
    $old = $data_old['value'];
    $new = mysqli_real_escape_string($conn, $data_new['value']);

    $token = $_POST['token'][0];
    $key = $_POST['token'][1];
    
    $jwt_token = decoder($token, $key);

    $stmt = mysqli_stmt_init($conn);

    $sql = "SELECT * FROM account WHERE AccountID = ?;";

    if(!$jwt_token){
        echo json_encode(array(
            'status' => 'Error',
            'message' => 'Your token is not authorized',
        ));
    }else{

        $jwt = json_decode(json_encode($jwt_token), true);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo json_encode(array(
                'status' => 'Error',
                'message' => 'Something went wrong',
            ));
        }else{  
            mysqli_stmt_bind_param($stmt, 's', $jwt['data']['acc_id']);
            if(!mysqli_stmt_execute($stmt)){
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'Something went wrong',
                ));
            }else{
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                $hash_from_db = $row['Password'];
                $check = password_verify($old, $hash_from_db);
                if(!$check){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Your current password does not match with the one in our system',
                    ));
                }else{
                    $sql = "UPDATE account SET Password = ? WHERE AccountID = ?;";
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        echo json_encode(array(
                            'status' => 'Error',
                            'message' => 'Something went wrong setting new password',
                        ));
                    }else{
                        $new_hash = password_hash($new, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, 'ss', $new_hash, $jwt['data']['acc_id']);
                        if(!mysqli_stmt_execute($stmt)){
                            echo json_encode(array(
                                'status' => 'Error',
                                'message' => 'Something went wrong setting new password',
                            ));
                        }else{
                            echo json_encode(array(
                                'status' => 'Success',
                                'message' => 'Successfully changed your password',
                            ));
                        }
                    }
                }
            }
        }

    }

}
?>