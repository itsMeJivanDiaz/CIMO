<?php
 function SetCapacity($stmt, $capacityid, $normalcap, $limitedcap){
    $sql = "INSERT INTO capacity (CapacityID, NormalCapacity, LimitedCapacity) VALUES (?, ?, ?);";
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return false;
    }else{
        mysqli_stmt_bind_param($stmt, 'sii', $capacityid, $normalcap, $limitedcap);
        if(mysqli_stmt_execute($stmt)){
            return true;
        }else{
            return false;
        }
    }
}
?>