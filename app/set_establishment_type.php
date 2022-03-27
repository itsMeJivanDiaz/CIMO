<?php
function SetEstablishmentType($stmt, $typeid, $est_type){
    $sql = "SELECT * FROM establishment_type WHERE EstablishmentType = ?;";
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return 'Error';
    }else{
        mysqli_stmt_bind_param($stmt, 's', $est_type);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        if($row > 0){
            return $row['TypeID'];
        }else{
            $sql1 = "INSERT INTO `establishment_type` (TypeID, EstablishmentType) VALUES (?, ?);";
            if(!mysqli_stmt_prepare($stmt, $sql1)){
                return 'Error';
            }else{
                mysqli_stmt_bind_param($stmt, 'ss', $typeid, $est_type);
                if(mysqli_stmt_execute($stmt)){
                    return 'Inserted';
                }else{
                    return 'Error';
                }
            }
        }
    }
}
