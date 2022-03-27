<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
if(isset($_POST['user-login'])){
    
    function GenerateSecretkey($length_of_string) { 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result),  0, $length_of_string); 
    } 

    require 'db.php';

    $username = $_POST['user-login'];
    $password = $_POST['pass-login'];
    $online = "online";
    $secret_key = GenerateSecretkey(60);
    $stmt = mysqli_stmt_init($conn);

    $sql = "SELECT * FROM account WHERE Username = ?;";

    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo json_encode(array(
            'status' => 'Log-In System Encountered Some Error :x',
            'token' => 'Token Error',
        ));
        exit();
    }else{
        mysqli_stmt_bind_param($stmt, 's', $username);
        if(!mysqli_stmt_execute($stmt)){
            echo json_encode(array(
                'status' => 'Log-In System Encountered Some Error :x',
                'token' => 'Token Error',
            ));
            exit();
        }else{
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            if($row > 0){
                $hashpass = $row['Password'];
                $acc_id = $row['AccountID'];
                $token_id = $row['TokenID'];
                $consent = $row['AccountStatus'];
                $password_verify = password_verify($password, $hashpass);
                if($password_verify){
                    if($consent == "Pending"){
                        echo json_encode(array(
                            'status' => 'Your Account Is Not Yet Validated By Your Designated Barangay :x',
                            'token' => 'Token Error',
                        ));
                        exit();
                    }else{
                        $sql = "SELECT * FROM establishment WHERE AccountID = ?;";
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            echo json_encode(array(
                                'status' => 'Fetching Account Information Encountered Some Error :x',
                                'token' => 'Token Error',
                            ));
                            exit();
                        }else{
                            mysqli_stmt_bind_param($stmt, 's', $acc_id);
                            if(!mysqli_stmt_execute($stmt)){
                                echo json_encode(array(
                                    'status' => 'Fetching Account Information Encountered Some Error :x',
                                    'token' => 'Token Error',
                                ));
                                exit();
                            }else{
                                $result = mysqli_stmt_get_result($stmt);
                                $row = mysqli_fetch_assoc($result);
                                $typeid = $row['TypeID'];
                                $addressid = $row['AddressID'];
                                $capacityid = $row['CapacityID'];
                                $countid = $row['CountID'];
                                $areaid = $row['AreaID'];

                                $sql = "SELECT * 
                                FROM establishment 
                                INNER JOIN establishment_type 
                                ON establishment.TypeID = establishment_type.TypeID
                                INNER JOIN address
                                ON establishment.AddressID = address.AddressID
                                INNER JOIN capacity
                                ON establishment.CapacityID = capacity.CapacityID
                                INNER JOIN `count`
                                ON establishment.CountID = count.CountID
                                INNER JOIN area
                                ON establishment.AreaID = area.AreaID 
                                WHERE establishment.TypeID = ? 
                                AND establishment.AddressID = ?
                                AND establishment.CapacityID = ?
                                AND establishment.CountID = ?
                                AND establishment.AreaID = ?";
                                
                                if(!mysqli_stmt_prepare($stmt, $sql)){
                                    echo json_encode(array(
                                        'status' => 'Fetching Account Information Encountered Some Error :x',
                                        'token' => 'Token Error',
                                    ));
                                    exit();
                                }else{
                                    mysqli_stmt_bind_param($stmt, 'sssss', $typeid, $addressid, $capacityid, $countid, $areaid);
                                    if(!mysqli_stmt_execute($stmt)){
                                        echo json_encode(array(
                                            'status' => 'Fetching Account Information Encountered Some Error :x',
                                            'token' => 'Token Error',
                                        ));
                                        exit();
                                    }else{
                                        $result = mysqli_stmt_get_result($stmt);
                                        $row = mysqli_fetch_assoc($result);
                                        
                                        $sql = "SELECT * FROM coordinate WHERE CoordinateID = ?;";

                                        if(!mysqli_stmt_prepare($stmt, $sql)){
                                            echo json_encode(array(
                                                'status' => 'Fetching Account Information Encountered Some Error :x',
                                                'token' => 'Token Error',
                                            ));
                                            exit();
                                        }else{
                                            mysqli_stmt_bind_param($stmt, 's', $row['CoordinateID']);
                                            if(!mysqli_stmt_execute($stmt)){
                                                echo json_encode(array(
                                                    'status' => 'Fetching Account Information Encountered Some Error :x',
                                                    'token' => 'Token Error',
                                                ));
                                            }else{
                                                $result = mysqli_stmt_get_result($stmt);
                                                $row_ = mysqli_fetch_assoc($result);

                                                $sql = "SELECT * FROM authentication_token WHERE TokenID = ?;";

                                                if(!mysqli_stmt_prepare($stmt, $sql)){
                                                    echo json_encode(array(
                                                        'status' => 'Fetching Account Information Encountered Some Error :x',
                                                        'token' => 'Token Error',
                                                    ));
                                                }else{
                                                    mysqli_stmt_bind_param($stmt, 's', $token_id);
                                                    if(!mysqli_stmt_execute($stmt)){
                                                        echo json_encode(array(
                                                            'status' => 'Fetching Account Information Encountered Some Error :x',
                                                            'token' => 'Token Error',
                                                        ));
                                                    }else{

                                                        $result1 = mysqli_stmt_get_result($stmt);
                                                        $row2 = mysqli_fetch_assoc($result1);

                                                        if($row2['TokenStatus'] == 'online'){
                                                            echo json_encode(array(
                                                                'status' => 'Sensing Some Danger :x',
                                                                'token' => 'Token Error',
                                                            ));
                                                        }else if($consent == 'Violation'){
                                                            echo json_encode(array(
                                                                'status' => 'Your establishment is under violation',
                                                                'token' => 'Token Error',
                                                            ));
                                                        }else if($consent == 'Denied'){
                                                            echo json_encode(array(
                                                                'status' => 'Please submit a new registration with verbally agreed parameters',
                                                                'token' => 'Token Error',
                                                            ));
                                                        }else{
                                                            $issuer = $_SERVER['SERVER_NAME'];
                                                            $issued_at = time();
                                                            $exp = time() * 168;
                                                            
                                                            $token_array = array(
                                                                'iss' => $issuer,
                                                                'exp' => $exp,
                                                                'iat' => $issued_at,
                                                                'data' => array(
                                                                    'est_id' => $row['EstablishmentID'],
                                                                    'est_name' => $row['EstablishmentName'],
                                                                    'est_logo' => $row['EstablishmentLogo'],
                                                                    'acc_id' => $row['AccountID'],
                                                                    'type_id' => $row['TypeID'],
                                                                    'est_type' => $row['EstablishmentType'],
                                                                    'add_id' => $row['AddressID'],
                                                                    'tok_id' => $token_id,
                                                                    'region' => $row['Region'],
                                                                    'province' => $row['Province'],
                                                                    'city' => $row['City'],
                                                                    'barangay' => $row['Barangay'],
                                                                    'street' => $row['Street'],
                                                                    'branch' => $row['Branch'],
                                                                    'cap_id' => $row['CapacityID'],
                                                                    'normal_cap' => $row['NormalCapacity'],
                                                                    'limited_cap' => $row['LimitedCapacity'],
                                                                    'cnt_id' => $row['CountID'],
                                                                    'area_id' => $row['AreaID'],
                                                                    'coor_id' => $row_['CoordinateID'],
                                                                    'latitude' => $row_['Latitude'],
                                                                    'longitude' => $row_['Longitude'],
                                                                )
                                                            );

                                                            $jwt = JWT::encode($token_array, $secret_key);
                                                        
                                                            $sql = "UPDATE authentication_token SET Token = ?, TokenStatus = ? WHERE TokenID = ?;";
                    
                                                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                                                echo json_encode(array(
                                                                    'status' => 'Assigning Token Encountered Some Error :x',
                                                                    'token' => 'Token Error',
                                                                ));
                                                            }else{
                                                                mysqli_stmt_bind_param($stmt, 'sss', $jwt, $online, $token_id);
                                                                if(!mysqli_stmt_execute($stmt)){
                                                                    echo json_encode(array(
                                                                        'status' => 'Assigning Token Encountered Some Error :x',
                                                                        'token' => 'Token Error',
                                                                    ));
                                                                    exit();
                                                                }else{
                                                                    echo json_encode(array(
                                                                        'status' => 'Log-in Success',
                                                                        'token' => $jwt,
                                                                        'key' => $secret_key,
                                                                    ));
                                                                    exit();
                                                                }
                                                            } 
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }else{
                    echo json_encode(array(
                        'status' => 'Your password or username might be wrong :x',
                        'token' => 'Token Error',
                    ));
                }
            }else{
                echo json_encode(array(
                    'status' => 'The system can not find your account :x',
                    'token' => 'Token Error',
                ));
            }
        }
    }
}

?>