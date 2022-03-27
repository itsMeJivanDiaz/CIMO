<?php
date_default_timezone_set('Asia/Manila');
header('Access-Control-Allow-Origin: http://localhost:8000');
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
if(isset($_POST['data'])){
    
    $token = $_POST['token'][0];
    $key = $_POST['token'][1];
    $data = $_POST['data'][0];
    $limit = $data['value'];

    $jwt = decoder($token, $key);
    $stmt = mysqli_stmt_init($conn);

    if(!$jwt){
        echo json_encode(array(
            'status' => 'Error',
            'message' => 'Your token is invalid'
        ));
    }else{

        $jwt_token = json_decode(json_encode($jwt), true);

        $capacity_id = $jwt_token['data']['cap_id'];
        $normal_cap = $jwt_token['data']['normal_cap'];

        $sql = "UPDATE capacity SET LimitedCapacity = ? WHERE CapacityID = ?;";
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo json_encode(array(
                'status' => 'Error',
                'message' => 'Update Error'
            ));
        }else{
            if(!is_numeric($limit)){

                $limitedcapacity = "0";

                mysqli_stmt_bind_param($stmt, 'si', $limitedcapacity, $capacity_id);

                mysqli_stmt_execute($stmt);

                $jwt_token['data']['limited_cap'] = $limitedcapacity;
                $new_jwt = encoder($jwt_token, $key);
                echo json_encode(array(
                    'status' => 'Success',
                    'message' => 'Unfortunately, You cant operate in this Status',
                    'token' => $new_jwt
                ));
            }else{

                $limitedcapacity = $normal_cap * $limit;

                mysqli_stmt_bind_param($stmt, 'si', $limitedcapacity, $capacity_id);

                if(!mysqli_stmt_execute($stmt)){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Update Error'
                    ));
                }else{ 
                    $jwt_token['data']['limited_cap'] = $limitedcapacity;
                    $new_jwt = encoder($jwt_token, $key);
                    echo json_encode(array(
                        'status' => 'Success',
                        'message' => 'Succesfully set limited capacity to ' . round($normal_cap * $limit),
                        'token' => $new_jwt
                    ));
                }
            }
        }
    }

}

?>