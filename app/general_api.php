<?php
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
header('Content-Type:application/json');

$stmt = mysqli_stmt_init($conn);

if(isset($_GET['all'])){

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

    $token = $_GET['token'];
    $key = $_GET['key'];
    $id = $_GET['id'];
    $accountstats = "Approved";
    $result_array = array();
    $error = array(
        'status' => 'Error'
    );
    $jwt = decoder($token, $key);
    $accountnot = "Denied";
    if($jwt){

        $sql = "SELECT * FROM establishment ORDER BY EstablishmentName ASC";
        
        $sql_coords = "SELECT * FROM coordinate WHERE CoordinateID = ?;";

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
                WHERE establishment.TypeID = ? 
                AND establishment.AddressID = ?
                AND establishment.CapacityID = ?
                AND establishment.CountID = ?
                AND establishment.AreaID = ?
                AND account.AccountStatus != ?;";
        if(!mysqli_stmt_prepare($stmt, $sql)){
            array_push($result_array, $error);
        }else{
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($res)){
                $typeid = $row['TypeID'];
                $addid = $row['AddressID'];
                $accid = $row['AccountID'];
                $capid = $row['CapacityID'];
                $cntid = $row['CountID'];
                $areaid = $row['AreaID'];
                if(!mysqli_stmt_prepare($stmt, $sql_join)){
                    array_push($result_array, $error);
                }else{
                    mysqli_stmt_bind_param($stmt, 'ssssss', $typeid, $addid, $capid, $cntid, $areaid, $accountnot);
                    if(!mysqli_stmt_execute($stmt)){
                        array_push($result_array, $error);
                    }else{
                        $res1 = mysqli_stmt_get_result($stmt);
                        while($row = mysqli_fetch_assoc($res1)){
                            $coordid = $row['CoordinateID'];
                            if(!mysqli_stmt_prepare($stmt, $sql_coords)){
                                array_push($result_array, $error);
                            }else{
                                mysqli_stmt_bind_param($stmt, 's', $coordid);
                                if(!mysqli_stmt_execute($stmt)){
                                    array_push($result_array, $error);
                                }else{
                                    $res_coords = mysqli_stmt_get_result($stmt);
                                    while($row_coords = mysqli_fetch_assoc($res_coords)){
                                        $array = array(
                                            'establishment-name' => $row['EstablishmentName'],
                                            'establishment-type' => $row['EstablishmentType'],
                                            'region' => ucwords($row['Region']),
                                            'province' => $row['Province'],
                                            'city' => format($row['City']),
                                            'barangay' => format_brgy($row['Barangay']),
                                            'street' => format_strt($row['Street']),
                                            'branch' => format($row['Branch']),
                                            'latitude' => $row_coords['Latitude'],
                                            'longitude' => $row_coords['Longitude'],
                                            'logo'=> $row['EstablishmentLogo'],
                                            'normal-capacity' => $row['NormalCapacity'],
                                            'limited-capacity' => $row['LimitedCapacity'],
                                            'establishment-ID' => $row['EstablishmentID'],
                                            'account-status' => $row['AccountStatus'],
                                        );
                                        array_push($result_array, $array);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            echo json_encode($result_array, JSON_PRETTY_PRINT);
        }
    }else{
        array_push($result_array, $error);
        echo json_encode($result_array, JSON_PRETTY_PRINT);
    }
}else if(isset($_GET['search'])){

    $search = $_GET['search'];

    $filter = $_GET['city'];
    
    $token = $_GET['token'];
    $key = $_GET['key'];
    $id = $_GET['id'];

    function format_filter($filt){
        return str_replace(' MUNICIPALITY', '', $filt);
    }

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


    $search_array = array();

    $error = array(
        'status' => 'Error'
    );

    $jwt = decoder($token, $key);

    if($jwt){

        $sql_search = "SELECT * FROM establishment WHERE EstablishmentName LIKE CONCAT(?,'%');";

        $sql_coords = "SELECT * FROM coordinate WHERE CoordinateID = ?;";

        if($filter == "None"){
            $sql_get_search_ID = "SELECT * FROM establishment INNER JOIN establishment_type 
                    on establishment.TypeID = establishment_type.TypeID
                    INNER JOIN address
                    on establishment.AddressID = address.AddressID
                    INNER JOIN capacity
                    on establishment.CapacityID = capacity.CapacityID
                    INNER JOIN count
                    on establishment.CountID = count.CountID
                    INNER JOIN area
                    on establishment.AreaID = area.AreaID
                    INNER JOIN account 
                    ON establishment.AccountID = account.AccountID
                    WHERE establishment.TypeID = ? 
                    AND establishment.AddressID = ?
                    AND establishment.CapacityID = ?
                    AND establishment.CountID = ?
                    AND establishment.AreaID = ?;";
        }else{
            $sql_get_search_ID = "SELECT * FROM establishment INNER JOIN establishment_type 
                    on establishment.TypeID = establishment_type.TypeID
                    INNER JOIN address
                    on establishment.AddressID = address.AddressID
                    INNER JOIN capacity
                    on establishment.CapacityID = capacity.CapacityID
                    INNER JOIN count
                    on establishment.CountID = count.CountID
                    INNER JOIN area
                    on establishment.AreaID = area.AreaID
                    INNER JOIN account 
                    ON establishment.AccountID = account.AccountID
                    WHERE establishment.TypeID = ? 
                    AND establishment.AddressID = ?
                    AND establishment.CapacityID = ?
                    AND establishment.CountID = ?
                    AND establishment.AreaID = ?
                    AND address.City = ?;";   
        }

        if(!mysqli_stmt_prepare($stmt, $sql_search)){
            array_push($search_array, $error);
        }else{
            mysqli_stmt_bind_param($stmt, 's', $search);
            if(!mysqli_stmt_execute($stmt)){
                array_push($search_array, $error);
            }else{
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($result)){
                    $typeid = $row['TypeID'];
                    $addid = $row['AddressID'];
                    $accid = $row['AccountID'];
                    $capid = $row['CapacityID'];
                    $cntid = $row['CountID'];
                    $areaid = $row['AreaID'];
                    if(!mysqli_stmt_prepare($stmt, $sql_get_search_ID)){
                        array_push($search_array, $error);
                    }else{
                        if($filter == "None"){
                            mysqli_stmt_bind_param($stmt, 'sssss', $typeid, $addid, $capid, $cntid, $areaid);
                        }else{
                            $new_filter = format_filter($filter);
                            mysqli_stmt_bind_param($stmt, 'ssssss', $typeid, $addid, $capid, $cntid, $areaid, $new_filter);
                        }
                        if(!mysqli_stmt_execute($stmt)){
                            array_push($search_array, $error);
                        }else{
                            $result_1 = mysqli_stmt_get_result($stmt);
                            while($row = mysqli_fetch_assoc($result_1)){
                                $coords = $row['CoordinateID'];
                                if(!mysqli_stmt_prepare($stmt, $sql_coords)){
                                    array_push($search_array, $error);
                                }else{
                                    mysqli_stmt_bind_param($stmt, 's', $coords);
                                    if(!mysqli_stmt_execute($stmt)){
                                        array_push($search_array, $error);
                                    }else{
                                        $res_cords = mysqli_stmt_get_result($stmt);
                                        while($row_coords = mysqli_fetch_assoc($res_cords)){
                                            $array = array(
                                                'establishment-name' => $row['EstablishmentName'],
                                                'establishment-type' => $row['EstablishmentType'],
                                                'region' => ucwords($row['Region']),
                                                'province' => $row['Province'],
                                                'city' => format($row['City']),
                                                'barangay' => format_brgy($row['Barangay']),
                                                'street' => format_strt($row['Street']),
                                                'branch' => format($row['Branch']),
                                                'latitude' => $row_coords['Latitude'],
                                                'longitude' => $row_coords['Longitude'],
                                                'logo'=> $row['EstablishmentLogo'],
                                                'normal-capacity' => $row['NormalCapacity'],
                                                'limited-capacity' => $row['LimitedCapacity'],
                                                'establishment-ID' => $row['EstablishmentID'],
                                                'account-status' => $row['AccountStatus'],
                                            );
                                            array_push($search_array, $array);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                echo json_encode($search_array);
            }
        }
    }else{
        array_push($search_array, $error);
        echo json_encode($search_array, JSON_PRETTY_PRINT);
    }
}else if($_GET['eid']){

    $token = $_GET['token'];
    $key = $_GET['key'];
    $id = $_GET['eid'];

    function format_filter($filt){
        return str_replace(' MUNICIPALITY', '', $filt);
    }

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

    $spec_array = array();

    $error = array(
        'status' => $id,
    );

    $jwt = decoder($token, $key);

    if($jwt){
        $sql = "SELECT * FROM establishment INNER JOIN establishment_type 
        on establishment.TypeID = establishment_type.TypeID 
        INNER JOIN address on establishment.AddressID = address.AddressID 
        INNER JOIN capacity on establishment.CapacityID = capacity.CapacityID 
        INNER JOIN count on establishment.CountID = count.CountID 
        INNER JOIN area on establishment.AreaID = area.AreaID 
        INNER JOIN coordinate on address.CoordinateID = coordinate.CoordinateID
        INNER JOIN account ON establishment.AccountID = account.AccountID 
        WHERE establishment.EstablishmentID = ?;";
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            array_push($spec_array, $error);
        }else{
            mysqli_stmt_bind_param($stmt, 's', $id);
            if(!mysqli_stmt_execute($stmt)){
                array_push($spec_array, $error);
            }else{
                $result_1 = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($result_1)){
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
                    array_push($spec_array, $array);
                }  
            }
        }
        echo json_encode($spec_array, JSON_PRETTY_PRINT);
    }else{
        array_push($spec_array, $error);
        echo json_encode($spec_array, JSON_PRETTY_PRINT);
    }
}
?>