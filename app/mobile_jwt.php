<?php
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
header('Content-Type:application/json');
if(isset($_GET['get_jwt'])){

    require 'db.php';

    function GenerateSecretkey($length_of_string) { 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result),  0, $length_of_string); 
    } 

    function random_strings($length_of_string) { 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result),  0, $length_of_string); 
    } 
    function random_ID($prefix){
        return uniqid($prefix) . random_strings(11);
    }

    $stmt = mysqli_stmt_init($conn);

    if($_GET['get_jwt'] == 'None'){

        $tokenstatus = "offline";

        $device_id = random_ID('IDdvc=');
            
        $tokenid = random_ID('IDtkn=');

        $result_array = array();

        $issuer = $_SERVER['SERVER_NAME'];

        $issued_at = time();

        $exp = time() * 168;
        
        $secret_key = GenerateSecretkey(60);

        $token_array = array(
            'iss' => $issuer,
            'exp' => $exp,
            'iat' => $issued_at,
            'data' => array(
                'dev_id' => $device_id,
                'tok_id' => $tokenid
            )
        );

        $jwt = JWT::encode($token_array, $secret_key);

        $output = array(
            'jwt' => $jwt,
            'key' => $secret_key,
            'tok_id' => $tokenid,
            'status' => 'new'
        );

        $sql = "INSERT INTO authentication_token (TokenID, Token, TokenStatus) VALUES (?, ?, ?);";
        
        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $tokenid, $jwt, $tokenstatus);
        mysqli_stmt_execute($stmt);

        $sql_dev = "INSERT INTO mobile_device (DeviceID, TokenID) VALUES (?, ?);";

        mysqli_stmt_prepare($stmt, $sql_dev);
        mysqli_stmt_bind_param($stmt, 'ss', $device_id, $tokenid);
        mysqli_stmt_execute($stmt);

        array_push($result_array, $output);

        echo json_encode($result_array, JSON_PRETTY_PRINT);

    }else if($_GET['get_jwt'] != 'None'){

        $online = "online";

        $result_array = array();
        
        $token = $_GET['get_jwt'];
        $key = $_GET['get_key'];
        $new_id = $_GET['get_id'];

        $error = array(
            'status' => 'Token error'
        );

        $sql = "SELECT * FROM authentication_token WHERE TokenID = ?;";

        if(!mysqli_stmt_prepare($stmt, $sql)){
            array_push($result_array, $error);
        }else{
            mysqli_stmt_bind_param($stmt, 's', $new_id);
            if(!mysqli_stmt_execute($stmt)){
                array_push($result_array, $error);
            }else{
                $res = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($res);
                if($row <= 0){
                    array_push($result_array, $error);
                }else{
                    if($row['TokenStatus'] == "online"){
                        array_push($result_array, $error);
                    }else{
                        $sql = "UPDATE authentication_token SET TokenStatus = ? WHERE TokenID = ?;";
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            array_push($result_array, $error);
                        }else{
                            mysqli_stmt_bind_param($stmt, 'ss', $online, $new_id);
                            if(!mysqli_stmt_execute($stmt)){
                                array_push($result_array, $error);
                            }else{
                                $new_array = array(
                                    'jwt' => $token,
                                    'key' => $key,
                                    'status' => 'Success',
                                );
                                array_push($result_array, $new_array);
                            }
                        }
                    }
                }
            }
        }
        echo json_encode($result_array, JSON_PRETTY_PRINT);
    }
}
?>