<?php
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
header('Content-Type:application/json');
if(isset($_POST['search'])){


    function format($text){
        $ret = strtolower($text);
        return ucwords($ret, ' [{(');
    }

    function format_strt($text){
        $ret = strtolower($text);
        $ret1 = ucwords($ret, '[{(');
        return $ret1 . ' Street';
    }

    function format_brgy($text){
        $word = 'Barangay';
        $ret = strtolower($text);
        $ret1 = ucwords($ret, '(');
        if(strpos($ret1, $word) !== false){
            return $ret1;
        }else{
            return 'Barangay ' .$ret1;
        }
    }

    $token = $_POST['token'];
    $key = $_POST['key'];
    $search = $_POST['search'];
    $result_array = array();
    $stmt = mysqli_stmt_init($conn);
    
    $jwt = decoder($token, $key);

    if($jwt){
        $jwt_token = json_decode(json_encode($jwt), true);
        $region = $jwt_token['data']['region'];
        $province = $jwt_token['data']['province'];
        $city = $jwt_token['data']['city'];
        $brgy = $jwt_token['data']['brgy'];
        $none = "None";
        $sql_search = "SELECT * FROM establishment INNER JOIN establishment_type 
        ON establishment.TypeID = establishment_type.TypeID
        INNER JOIN address
        ON establishment.AddressID = address.AddressID
        INNER JOIN capacity
        ON establishment.CapacityID = capacity.CapacityID
        INNER JOIN count
        ON establishment.CountID = count.CountID
        INNER JOIN area
        ON establishment.AreaID = area.AreaID
        INNER JOIN account
        ON establishment.AccountID = account.AccountID
        INNER JOIN establishment_type AS es
        ON establishment.TypeID = es.TypeID
        INNER JOIN coordinate 
        ON address.CoordinateID = coordinate.CoordinateID
        WHERE establishment.EstablishmentName LIKE CONCAT(?,'%')
        AND address.Region = ?
        AND address.Province = ?
        AND address.City = ?
        AND address.Barangay = ?
        AND address.Street != ?
        AND address.Branch != ?;";
        if(!mysqli_stmt_prepare($stmt, $sql_search)){
            array_push($result_array, array(
                'status' => 'Error'
            ));
            echo json_encode($result_array, JSON_PRETTY_PRINT);
        }else{
            mysqli_stmt_bind_param($stmt, 'sssssss', $search, $region, $province, $city, $brgy, $none, $none);
            if(!mysqli_stmt_execute($stmt)){
                array_push($result_array, array(
                    'status' => 'Error'
                ));
                echo json_encode($result_array, JSON_PRETTY_PRINT);
            }else{
                $s_result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($s_result)){
                    $array = array(
                        'establishment-name' => $row['EstablishmentName'],
                        'establishment-type' => $row['EstablishmentType'],
                        'region' => ucwords($row['Region']),
                        'province' => $row['Province'],
                        'city' => format($row['City']),
                        'barangay' => format_brgy($row['Barangay']),
                        'street' => format_strt($row['Street']),
                        'branch' => format($row['Branch']),
                        'latitude' => $row['Latitude'],
                        'longitude' => $row['Longitude'],
                        'logo'=> $row['EstablishmentLogo'],
                        'normal-capacity' => $row['NormalCapacity'],
                        'limited-capacity' => $row['LimitedCapacity'],
                        'establishment-ID' => $row['EstablishmentID'],
                        'square-meters' => $row['SquareMeters'],
                        'account-status' => $row['AccountStatus'],
                    );
                    array_push($result_array, $array);
                }
                echo json_encode($result_array, JSON_PRETTY_PRINT);
            }
        }
    }else{
        array_push($result_array, array(
            'status' => 'Token error'
        ));
        echo json_encode($result_array, JSON_PRETTY_PRINT);
    }

}
?>