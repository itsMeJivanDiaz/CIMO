<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
if(isset($_POST['token'])){
    require 'jwt_decode.php';
    require 'db.php';
    $result_array = array();
    $token = $_POST['token'];
    $key = $_POST['key'];
    $jwt = decoder($token, $key);

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

    function format($text){
        $ret = strtolower($text);
        return ucwords($ret, ' [{(');
    }

    if($jwt){
        $jwt_token = json_decode(json_encode($jwt), true);
        $region = $jwt_token['data']['region'];
        $province = $jwt_token['data']['province'];
        $city = $jwt_token['data']['city'];
        $brgy = $jwt_token['data']['brgy'];
        $none = "None";
        $approved = "Approved";
        $denied = "Denied";
        $stmt = mysqli_stmt_init($conn);
        $sql = "SELECT * FROM address WHERE Region =? AND Province = ? AND City = ? AND Barangay = ? AND Street != ? AND Branch != ? ORDER BY AddressID ASC;";
        $sql_join = "SELECT * FROM establishment INNER JOIN establishment_type 
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
        WHERE establishment.AddressID = ?
        AND account.AccountStatus != ?;";
        if(!mysqli_stmt_prepare($stmt, $sql)){
            array_push($result_array, array(
                'error' => 'Display Error'
            ));
        }else{
            mysqli_stmt_bind_param($stmt, 'ssssss', $region, $province, $city, $brgy, $none, $none);
            if(!mysqli_stmt_execute($stmt)){
                array_push($result_array, array(
                    'error' => 'Display Error'
                ));
            }else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($result)){
                    $addresid = $row['AddressID'];
                    if(!mysqli_stmt_prepare($stmt, $sql_join)){
                        array_push($result_array, array(
                            'error' => 'Fetch error'
                        ));
                        echo json_encode($result_array);
                    }else{
                        mysqli_stmt_bind_param($stmt, 'ss', $addresid, $denied);
                        if(!mysqli_stmt_execute($stmt)){
                            array_push($result_array, array(
                                'error' => 'Fetch error'
                            ));
                            echo json_encode($result_array);
                        }else{
                            $res = mysqli_stmt_get_result($stmt);
                            while($row1 = mysqli_fetch_assoc($res)){
                                $array = array(
                                    'establishment-name' => $row1['EstablishmentName'],
                                    'establishment-type' => $row1['EstablishmentType'],
                                    'region' => ucwords($row['Region']),
                                    'province' => $row['Province'],
                                    'city' => format($row['City']),
                                    'barangay' => format_brgy($row['Barangay']),
                                    'street' => format_strt($row['Street']),
                                    'branch' => format($row['Branch']),
                                    'latitude' => $row1['Latitude'],
                                    'longitude' => $row1['Longitude'],
                                    'logo'=> $row1['EstablishmentLogo'],
                                    'normal-capacity' => $row1['NormalCapacity'],
                                    'limited-capacity' => $row1['LimitedCapacity'],
                                    'establishment-ID' => $row1['EstablishmentID'],
                                    'square-meters' => $row1['SquareMeters'],
                                    'account-status' => $row1['AccountStatus']
                                );
                                array_push($result_array, $array);
                            }
                        }
                    }
                }
                 echo json_encode($result_array);
            }
        }
    }else{
        array_push($result_array, array(
            'error' => 'Invalid token'
        ));
        echo json_encode($result_array);
    }
}
?>