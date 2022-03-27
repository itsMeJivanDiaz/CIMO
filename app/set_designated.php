<?php
function SetDesignated($stmt, $designatedid, $accid, $addrid){
    $sql = "INSERT INTO designated_barangay (DesignatedBarangayID, AccountID, AddressID)  VALUES (?, ?, ?);";
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return false;
    }else{
        mysqli_stmt_bind_param($stmt, 'sss', $designatedid, $accid, $addrid);
        if(!mysqli_stmt_execute($stmt)){
            return false;
        }else{
            return true;
        }
    }
}
?>