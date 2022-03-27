<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
if(isset($_POST['data'])){
    require 'db.php';
    function GenerateSecretkey($length_of_string) { 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result),  0, $length_of_string); 
    } 
    $username = $_POST['username'];
    $password = $_POST['password'];
    $token_array = array();
    $secretkey = GenerateSecretkey(60);
    $online = "online";
    $sql = "SELECT * FROM account WHERE Username = ?;";
    $sql_j = "SELECT * FROM `designated_barangay` 
    INNER JOIN account ON designated_barangay.AccountID = account.AccountID 
    INNER JOIN address on designated_barangay.AddressID = address.AddressID
    INNER JOIN authentication_token ON account.TokenID = authentication_token.TokenID
    WHERE account.AccountID = ?;";
    $sql_u = "UPDATE authentication_token SET Token = ?, TokenStatus = ? WHERE TokenID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        array_push($token_array, array(
            'error' => 'Server error',
        ));
        echo json_encode($token_array);
    }else{
        mysqli_stmt_bind_param($stmt, 's', $username);
        if(!mysqli_stmt_execute($stmt)){
            array_push($token_array, array(
                'error' => 'Server error',
            ));
            echo json_encode($token_array);
        }else{
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            if($row <= 0){
                array_push($token_array, array(
                    'error' => 'User not found',
                ));
                echo json_encode($token_array);
            }else{
                $hashed = $row['Password'];
                $check = password_verify($password, $hashed);
                if($check){
                    $accid = $row['AccountID'];
                    if(!mysqli_stmt_prepare($stmt, $sql_j)){
                        array_push($token_array, array(
                            'error' => 'Fethcing info failed',
                        ));
                        echo json_encode($token_array);
                    }else{
                        mysqli_stmt_bind_param($stmt, 's', $accid);
                        if(!mysqli_stmt_execute($stmt)){
                            array_push($token_array, array(
                                'error' => 'Fethcing info failed',
                            ));
                            echo json_encode($token_array);
                        }else{
                            $res = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_assoc($res);  
                            $issuer = $_SERVER['SERVER_NAME'];
                            $issued_at = time();
                            $exp = time() * 168;
                            $array = array(
                                'iss' => $issuer,
                                'exp' => $exp,
                                'iat' => $issued_at,
                                'data' => array(
                                    'tok_id' => $row['TokenID'],
                                    'accid' => $row['AccountID'],
                                    'addressid' => $row['AddressID'],
                                    'region' => $row['Region'],
                                    'province' => $row['Province'],
                                    'city' => $row['City'],
                                    'brgy' => $row['Barangay'],
                                ),
                            );
                            $jwt = JWT::encode($array, $secretkey);
                            $final_token = array(
                                'jwt' => $jwt,
                                'secretkey' => $secretkey,
                                'status' => 'Success'
                            );

                            if(!mysqli_stmt_prepare($stmt, $sql_u)){
                                array_push($token_array, array(
                                    'error' => 'Fetching info failed',
                                ));
                                echo json_encode($token_array);
                            }else{
                                if($row['TokenStatus'] == "online"){
                                    array_push($token_array, array(
                                        'error' => 'Token is in use',
                                    ));
                                    echo json_encode($token_array);
                                }else{
                                    mysqli_stmt_bind_param($stmt, 'sss', $jwt, $online, $row['TokenID']);
                                    if(!mysqli_stmt_execute($stmt)){
                                        array_push($token_array, array(
                                            'error' => 'Fetching info failed',
                                        ));
                                        echo json_encode($token_array);
                                    }else{
                                        array_push($token_array, $final_token);
                                        echo json_encode($token_array, JSON_PRETTY_PRINT);
                                    }
                                }
                            }
                        }
                    }
                }else{
                    array_push($token_array, array(
                        'error' => 'Password does not match',
                    ));
                    echo json_encode($token_array);
                }
            }
        }
    }
}
?>