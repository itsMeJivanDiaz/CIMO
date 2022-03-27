<?php
function SetArea($stmt, $areaid, $areasq){
    $sql = "INSERT INTO area (AreaID, SquareMeters) VALUES (?, ?);";
    if(mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, 'si', $areaid, $areasq);
        if(mysqli_stmt_execute($stmt)){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
?>