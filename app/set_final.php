<?php
function SetFinal($stmt, $estid, $estname, $estlogo, $typeid_get, $typeid, $accid, $addressid, $capacityid, $countid, $areaid){
    $sql = "INSERT INTO `establishment`(`EstablishmentID`, `EstablishmentName`, `EstablishmentLogo`, `TypeID`, `AccountID`, `AddressID`, `CapacityID`, `CountID`, `AreaID`) VALUES (?,?,?,?,?,?,?,?,?);";
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo json_encode('x');
    }else{
        if($typeid_get != 'Inserted'){
            mysqli_stmt_bind_param($stmt, 'sssssssss', $estid, $estname, $estlogo, $typeid_get, $accid, $addressid, $capacityid, $countid, $areaid);
            if(mysqli_stmt_execute($stmt)){
                return true;
            }else{
                return false;
            }
        }else{
            mysqli_stmt_bind_param($stmt, 'sssssssss', $estid, $estname, $estlogo, $typeid, $accid, $addressid, $capacityid, $countid, $areaid);
            if(mysqli_stmt_execute($stmt)){
                return true;
            }else{
                return false;
            }
        }
    }
}
?>