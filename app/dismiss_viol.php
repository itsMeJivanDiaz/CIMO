<?php
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
header('Content-Type:application/json');
if(isset($_GET['token'])){
    $result_array = array();
    $token = $_GET['token'];
    $key = $_GET['key'];
    $acc_id = $_GET['id'];
    $violid = $_GET['violid'];
    $jwt = decoder($token, $key);
    if($jwt){
        $approved = "Approved";
        $dismissed = "Dismissed";
        $jwt_token = json_decode(json_encode($jwt), true);
        $stmt = mysqli_stmt_init($conn);
        $sql = "UPDATE account SET AccountStatus = ? WHERE AccountID = ?;";
        if(!mysqli_stmt_prepare($stmt, $sql)){
            array_push($result_array, array('status' => 'Something went wrong2'));
            echo json_encode($result_array);
        }else{
            mysqli_stmt_bind_param($stmt, 'ss', $approved, $acc_id);
            if(!mysqli_stmt_execute($stmt)){
                array_push($result_array, array('status' => 'Something went wrong1'));
                echo json_encode($result_array);
            }else{
                $sql_update = "UPDATE violation SET ViolationStatus = ? WHERE ViolationID = ?;";
                mysqli_stmt_prepare($stmt, $sql_update);
                mysqli_stmt_bind_param($stmt, 'ss', $dismissed, $violid);
                mysqli_stmt_execute($stmt);
                array_push($result_array, array('status' => 'Success'));
                echo json_encode($result_array);
            }
        }
    }
}
?>