<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
if(isset($_POST['data'])){
    function random_strings($length_of_string) { 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result),  0, $length_of_string); 
    } 
    function random_ID($prefix){
        return uniqid($prefix) . random_strings(11);
    }
    $process = $_POST['data'][0];
    $value = $_POST['data'][1];
    $stmt = mysqli_stmt_init($conn);
    $token = decoder($_POST['token'][0], $_POST['token'][1]);
    if($token){
        $jwt_token = json_decode(json_encode($token), true);
        if($process['value'] == "edit_name"){
            $sql = "UPDATE establishment SET EstablishmentName = ? WHERE EstablishmentID = ?;";
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'Unfortunately, something went wrong',
                ));
            }else{
                $new_name = $value['value'];
                mysqli_stmt_bind_param($stmt, 'ss', $new_name, $jwt_token['data']['est_id']);
                if(!mysqli_stmt_execute($stmt)){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Unfortunately, something went wrong',
                    ));
                }else{
                    $jwt_token['data']['est_name'] = $new_name;   
                    $new_jwt = encoder($jwt_token, $_POST['token'][1]);
                    echo json_encode(array(
                        'status' => 'Success',
                        'message' => 'You have successfully updated your <br>establishment name',
                        'set' => 'est_name',
                        'data' => $new_name,
                        'token' => $new_jwt,
                    ));
                }
            }
        }elseif($process['value'] == "edit_type"){
            $typeid = random_ID('IDtyp=');
            $type = strtolower($value['value']);
            if($jwt_token['data']['est_type'] == $type){
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'Your current establishment type is in sync <br> with the one you requested',
                ));
            }else{
                $sql1= "SELECT * FROM establishment_type WHERE EstablishmentType = ?;";
                if(!mysqli_stmt_prepare($stmt, $sql1)){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Unfortunately, something went wrong',
                    ));
                }else{
                    mysqli_stmt_bind_param($stmt, 's', $type);
                    if(!mysqli_stmt_execute($stmt)){
                        echo json_encode(array(
                            'status' => 'Error',
                            'message' => 'Unfortunately, something went wrong',
                        ));
                    }else{
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        if($row > 0){
                            $sql = "UPDATE establishment SET TypeID = ?;";
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                 echo json_encode(array(
                                    'status' => 'Error',
                                    'message' => 'Unfortunately, something went wrong',
                                ));
                            }else{
                                mysqli_stmt_bind_param($stmt, 's', $row['TypeID']);
                                if(!mysqli_stmt_execute($stmt)){
                                    echo json_encode(array(
                                        'status' => 'Error',
                                        'message' => 'Unfortunately, something went wrong1',
                                    ));
                                }else{
                                    $jwt_token['data']['est_type'] = $type;
                                    $jwt_token['data']['type_id'] = $row['TypeID'];
                                    $new_jwt = encoder($jwt_token, $_POST['token'][1]);
                                    echo json_encode(array(
                                        'status' => 'Success',
                                        'message' => 'You have successfully updated your <br>establishment type',
                                        'set' => 'est_type',
                                        'data' => $type,
                                        'token' => $new_jwt,
                                    ));
                                }
                            }
                        }else{
                            $sql = "INSERT INTO establishment_type (TypeID, EstablishmentType) VALUES (?, ?);";
                            if(!mysqli_stmt_prepare($stmt, $sql)){
                                echo json_encode(array(
                                    'status' => 'Error',
                                    'message' => 'Unfortunately, something went wrong',
                                ));
                            }else{
                                mysqli_stmt_bind_param($stmt, 'ss', $typeid, $type);
                                if(!mysqli_stmt_execute($stmt)){
                                    echo json_encode(array(
                                        'status' => 'Error',
                                        'message' => 'Unfortunately, something went wrong',
                                    ));
                                }else{
                                    $jwt_token['data']['est_type'] = $type;
                                    $jwt_token['data']['type_id'] = $typeid;
                                    $new_jwt = encoder($jwt_token, $_POST['token'][1]);
                                    echo json_encode(array(
                                        'status' => 'Success',
                                        'message' => 'You have successfully updated your <br>establishment type',
                                        'set' => 'est_type',
                                        'data' => $type,
                                        'token' => $new_jwt,
                                    ));
                                }
                            }
                        }
                    }
                }
            }
        }elseif($process['value'] == "edit_address"){
            $region = $_POST['data'][1];
            $province = $_POST['data'][2];
            $city = $_POST['data'][3];
            $barangay = $_POST['data'][4];
            $region_en = mysqli_real_escape_string($conn, $region['value']);
            $province_en = mysqli_real_escape_string($conn, $province['value']);
            $city_en = mysqli_real_escape_string($conn, $city['value']);
            $barangay_en = mysqli_real_escape_string($conn, $barangay['value']);
            $sql = "UPDATE address SET Region =?, Province = ?, City = ?, Barangay = ? WHERE AddressID = ?;";
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'Unfortunately, something went wrong',
                ));
            }else{
                mysqli_stmt_bind_param($stmt, 'sssss', $region_en, $province_en, $city_en, $barangay_en, $jwt_token['data']['add_id']);
                if(!mysqli_stmt_execute($stmt)){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Unfortunately, something went wrong',
                    ));
                }else{
                    $jwt_token['data']['region'] = $region_en;
                    $jwt_token['data']['province'] = $province_en;
                    $jwt_token['data']['city'] = $city_en;
                    $jwt_token['data']['barangay'] = $barangay_en;
                    $new_jwt = encoder($jwt_token, $_POST['token'][1]);
                    echo json_encode(array(
                        'status' => 'Success',
                        'message' => 'You have successfully updated your <br>address information',
                        'set' => ['region', 'province', 'city', 'barangay'],
                        'data' => [$region_en, $province_en, $city_en, $barangay_en],
                        'token' => $new_jwt,
                    ));
                }
            }
        }elseif($process['value'] == "edit_street"){
            $street = $_POST['data'][1];
            $street_en = mysqli_real_escape_string($conn, strtoupper($street['value']));
            $sql = "UPDATE address SET Street = ? WHERE AddressID = ?;";
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'Unfortunately, something went wrong',
                ));
            }else{
                $street = $_POST['data'][1];
                mysqli_stmt_bind_param($stmt, 'ss', $street_en, $jwt_token['data']['add_id']);
                if(!mysqli_stmt_execute($stmt)){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Unfortunately, something went wrong',
                    ));
                }else{
                    $jwt_token['data']['street'] = $street_en;
                    $new_jwt = encoder($jwt_token, $_POST['token'][1]);
                    echo json_encode(array(
                        'status' => 'Success',
                        'message' => 'You have successfully updated your <br>street information',
                        'set' => 'street',
                        'data' => $street_en,
                        'token' => $new_jwt,
                    ));
                }
            }
        }elseif($process['value'] == "edit_branch"){
            $branch = $_POST['data'][1];
            $branch_en = mysqli_real_escape_string($conn, strtoupper($branch['value']));
            $sql = "UPDATE address SET Branch = ? WHERE AddressID = ?;";
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'Unfortunately, something went wrong',
                ));
            }else{
                mysqli_stmt_bind_param($stmt, 'ss', $branch_en, $jwt_token['data']['add_id']);
                if(!mysqli_stmt_execute($stmt)){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Unfortunately, something went wrong',
                    ));
                }else{
                    $jwt_token['data']['branch'] = $branch_en;
                    $new_jwt = encoder($jwt_token, $_POST['token'][1]);
                    echo json_encode(array(
                        'status' => 'Success',
                        'message' => 'You have successfully updated your <br>branch information',
                        'set' => 'branch',
                        'data' => $branch_en,
                        'token' => $new_jwt
                    ));
                }
            }
        }elseif($process['value'] == "edit_coords"){
            $coords = $_POST['data'][1];
            $coords_en = mysqli_real_escape_string($conn, strtoupper($coords['value']));
            $lat_long = mysqli_real_escape_string($conn, $coords_en);
            $coordinates = explode(', ', $lat_long);
            if(sizeof($coordinates) != 2){
                echo json_encode(array(
                    'status' => 'Your coordinates is incomplete.',
                ));
                return false;
            }
            $lat = $coordinates[0];
            $long = $coordinates[1];
            $sql = "UPDATE coordinate SET Latitude = ?, Longitude = ? WHERE CoordinateID = ?";
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'Unfortunately, something went wrong',
                ));
            }else{
                mysqli_stmt_bind_param($stmt, 'dds', $lat, $long, $jwt_token['data']['coor_id']);
                if(!mysqli_stmt_execute($stmt)){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Unfortunately, something went wrong',
                    ));
                }else{
                    $jwt_token['data']['latitude'] = $lat;
                    $jwt_token['data']['longitude'] = $long;
                    $new_jwt = encoder($jwt_token, $_POST['token'][1]);
                    echo json_encode(array(
                        'status' => 'Success',
                        'message' => 'You have successfully updated your <br>coordinate information',
                        'set' => 'none',
                        'data' => 'none',
                        'token' => $new_jwt,
                    ));
                }
            }
        }
    }else{  
        echo json_encode(array(
            'status' => 'Error',
            'message' => 'Unfortunately, something went wrong with <br>your token',
        ));
    }
}
?>