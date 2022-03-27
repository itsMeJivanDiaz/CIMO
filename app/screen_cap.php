<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
if(isset($_POST['data'])){
    function random_strings($length_of_string) { 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        return substr(str_shuffle($str_result),  0, $length_of_string); 
    } 
    function random_ID($prefix){
        return uniqid($prefix) . random_strings(11);
    }
    $filtered = substr($_POST['data'], strpos($_POST['data'], ',')+1);
    $decoded = base64_decode($filtered);
    $filename = random_ID('IDmg=');
    file_put_contents('../screen_shot_logs/'.$filename.'.png', $decoded);
    echo json_encode($filtered);
}
?>