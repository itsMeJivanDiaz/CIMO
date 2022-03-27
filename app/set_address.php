<?php
function SetAddress($stmt, $addressid, $region, $province, $city, $barangay, $street, $branch, $coordinateid){
    $sql = "INSERT INTO `address` (AddressID, Region, Province, City, Barangay, Street, Branch, CoordinateID) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return false;
    }else{
        mysqli_stmt_bind_param($stmt, 'ssssssss', $addressid, $region, $province, $city, $barangay, $street, $branch, $coordinateid);
        if(mysqli_stmt_execute($stmt)){
           return true;
        }else{
            return false;
        }

    }
}
?>