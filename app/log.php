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

    $log_id = random_ID('IDlog=');
    $est_id = $_POST['est_id'];
    $base64 = $_POST['base_64'];
    $filter = substr($base64, strpos($base64, ',')+1);
    $img_decoded = base64_decode($filter);
    $filename = random_ID('lgimg=').'.png';
    $video_name = mysqli_real_escape_string($conn, $_POST['vid_name']);
    $date_time = date('Y-m-d h:i:s');


    $stmt = mysqli_stmt_init($conn);

    $sql = "INSERT INTO log (LogID, LogVideoUrl, EstablishmentID, LogScreenCapture, DateAndTime) VALUES (?, ?, ?, ?, ?);";

    file_put_contents('../screen_shot_logs/'.$filename, $img_decoded);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'sssss', $log_id, $video_name, $est_id, $filename, $date_time);
    mysqli_stmt_execute($stmt);
    echo json_encode(array(
        'status' => 'success'
    ));

}
?>
