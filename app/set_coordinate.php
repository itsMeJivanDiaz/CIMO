<?php
function SetCoordinate($stmt, $coordinateid, $lat, $long){
    $sql = "INSERT INTO coordinate (CoordinateID, Latitude, Longitude) VALUES (?, ?, ?);";    
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return false;
    }else{
        mysqli_stmt_bind_param($stmt, 'sdd', $coordinateid, $lat, $long);
        if(mysqli_stmt_execute($stmt)){
            return true;
        }else{
            return false;
        }
    }
}
?>