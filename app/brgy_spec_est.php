<?php
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
header('Content-Type:application/json');
if(isset($_GET['est_id'])){
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
    
    $key = $_GET['key'];
    $token = $_GET['token'];
    $jwt = decoder($token, $key);
    $result_array = array();
    if($jwt){
        $est_id = $_GET['est_id'];
        $stmt = mysqli_stmt_init($conn);
        $sql = "SELECT * FROM establishment INNER JOIN establishment_type 
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
        WHERE establishment.EstablishmentID = ?;";

        if(!mysqli_stmt_prepare($stmt, $sql)){
            array_push($result_array, array(
                'status' => 'Info Error'
            ));
            echo json_encode($result_array, JSON_PRETTY_PRINT);
        }else{
            mysqli_stmt_bind_param($stmt, 's', $est_id);
            if(!mysqli_stmt_execute($stmt)){
                array_push($result_array, array(
                    'status' => 'Fetching Error'
                ));
                echo json_encode($result_array, JSON_PRETTY_PRINT);
            }else{
                $res = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($res)){
                    $status = 'Fetching';
                    $available = $row['LimitedCapacity'] - $row['Counter'];
                    if($row['AccountStatus'] == "Violation"){
                        $status = 'violation';
                        $available = 0;
                    }else if($row['Counter'] < $row['LimitedCapacity']){
                        $status = 'normal';
                    }else if($row['Counter'] == $row['LimitedCapacity']){
                        $status = 'full';
                    }else if($row['Counter'] > $row['LimitedCapacity']){
                        $status = 'violation';
                        $available = 0;
                    }
                    $array = array(
                        'status' => $status,
                        'establishment-name' => $row['EstablishmentName'],
                        'establishment-type' => $row['EstablishmentType'],
                        'region' => ucwords($row['Region']),
                        'province' => format($row['Province']),
                        'city' => format($row['City']),
                        'barangay' => format_brgy($row['Barangay']),
                        'street' => format_strt($row['Street']),
                        'branch' => format($row['Branch']),
                        'latitude' => $row['Latitude'],
                        'longitude' => $row['Longitude'],
                        'logo'=> $row['EstablishmentLogo'],
                        'currentcount' => $row['CurrentCount'],
                        'total' => $row['Counter'],
                        'available' => $available,
                        'normal-capacity' => $row['NormalCapacity'],
                        'limited-capacity' => $row['LimitedCapacity'],
                        'establishment-ID' => $row['EstablishmentID'],
                    );
                    array_push($result_array, $array);
                }
                echo json_encode($result_array, JSON_PRETTY_PRINT);
            }
        }
    }else{
        array_push($result_array, array(
            'status' => 'Token Error'
        ));
        echo json_encode($result_array, JSON_PRETTY_PRINT);
    }
}
?>