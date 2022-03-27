<?php
function SetAccount($stmt, $accid, $password, $username, $email, $consent, $typeid, $tokenid, $datecreated){
    $sql = "SELECT * FROM account WHERE username = ?;";
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return false;
    }else{
        mysqli_stmt_bind_param($stmt, 's', $username);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            if($row > 0){
                $sql_del = "DELETE FROM authentication_token WHERE TokenID = ?;";
                mysqli_stmt_prepare($stmt, $sql_del);
                mysqli_stmt_bind_param($stmt, 's', $tokenid);
                mysqli_stmt_execute($stmt);
                return false;
            }else{
                $sql = "INSERT INTO account (AccountID, Password, Username, Email, AccountStatus, AccountTypeID, TokenID, DateCreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    return false;
                }else{
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, 'ssssssss', $accid, $hashed, $username, $email, $consent, $typeid, $tokenid, $datecreated);
                    if(!mysqli_stmt_execute($stmt)){
                        return false;
                    }else{
                        return true;
                    }
                }
            }
        }else{
            return false;
        }
    }
}
?>