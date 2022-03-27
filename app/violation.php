<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
if(isset($_POST['data'])){
    
    require 'db.php';

    function random_strings($length_of_string) { 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result),  0, $length_of_string); 
    } 
    function random_ID($prefix){
        return uniqid($prefix) . random_strings(11);
    }

    $viol_id = random_ID('IDvls=');
    $est_id = $_POST['est_id'];
    $base64 = $_POST['base_64'];
    $filter = substr($base64, strpos($base64, ',')+1);
    $img_decoded = base64_decode($filter);
    $filename = random_ID('vlimg=').'.png';
    $video_name = mysqli_real_escape_string($conn, $_POST['vid_name']);
    $acc_id = $_POST['acc_id'];
    $date_time = date('Y-m-d h:i:s');
    $violation = "Violation";
    $violationstatus = "Pending";
    $stmt = mysqli_stmt_init($conn);

    $sql = "INSERT INTO violation (ViolationID, ViolationVideoUrl, ViolationScreenCapture, EstablishmentID, ViolationStatus, DateAndTime) VALUES (?, ?, ?, ?, ?, ?);";
    $sql_violation = "UPDATE account SET AccountStatus = ? WHERE AccountID = ?;";

    file_put_contents('../screen_shot_violations/'.$filename, $img_decoded);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssss', $viol_id, $video_name, $filename, $est_id, $violationstatus, $date_time);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_prepare($stmt, $sql_violation);
    mysqli_stmt_bind_param($stmt, 'ss', $violation, $acc_id);
    mysqli_stmt_execute($stmt);
    echo json_encode(array(
        'status' => 'success'
    ));
}
?>
