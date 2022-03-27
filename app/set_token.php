<?php
function setToken($stmt, $tokenid, $token, $tokenstatus){
    $sql = "INSERT INTO authentication_token (TokenID, Token, TokenStatus) VALUE (?, ?, ?);";
    if(!mysqli_stmt_prepare($stmt, $sql)){
        return false;
    }else{
        $hashtoken = password_hash($token, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, 'sss', $tokenid, $hashtoken, $tokenstatus);
        if(mysqli_stmt_execute($stmt)){
            return true;
        }else{
            return false;
        }
    }
}
?>