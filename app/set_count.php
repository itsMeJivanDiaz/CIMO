<?php
function SetCount($stmt, $countid, $count){
    $sql = "INSERT INTO count (CountID, CurrentCount) VALUES (?, ?);";
    if(mysqli_stmt_prepare($stmt, $sql)){
        mysqli_stmt_bind_param($stmt, 'ss', $countid, $count);
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