<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
require 'db.php';
require 'jwt_decode.php';
require 'jwt_encoder.php';
if(isset($_FILES['file'])){

    $token = $_POST['token'];
    $key = $_POST['key'];
    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $filesize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    $fileExt = explode('.', $fileName);
    $fileActExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'gif');

    $sql_update = "UPDATE establishment SET EstablishmentLogo = ? WHERE EstablishmentID = ?;";

    $stmt = mysqli_stmt_init($conn);

    $jwt = decoder($token, $key);

    if(!$jwt){
        echo json_encode('Something Went Wrong');
    }else{

        $jwt_token = json_decode(json_encode($jwt), true);

        if(in_array($fileActExt, $allowed)){
            if($fileError === 0){
                if($filesize <= 10000000){
                    $fileNewName = uniqid('', true).".".$fileActExt;
                    $fileDestination = '../uploads/'.$fileNewName;
                    if(!mysqli_stmt_prepare($stmt, $sql_update)){
                        echo json_encode(array(
                            'status' => 'Something Went Wrong',
                            'message' => 'Unfortunately, something went wrong',
                        ));
                    }else{
                        mysqli_stmt_bind_param($stmt, 'ss', $fileNewName, $jwt_token['data']['est_id']);
                        if(!mysqli_stmt_execute($stmt)){
                            echo json_encode(array(
                                'status' => 'Something Went Wrong',
                                'message' => 'Unfortunately, something went wrong',
                            ));
                        }else{
                            move_uploaded_file($fileTmpName, $fileDestination);
                            $jwt_token['data']['est_logo'] = $fileNewName;
                            $new_jwt = encoder($jwt_token, $key);
                            echo json_encode(array(
                                'status' => 'Success',
                                'message' => 'You have successfully updated your <br>establishment logo',
                                'data_url' => $fileNewName,
                                'token' => $new_jwt
                            ));
                        }
                    }
                }else{
                    echo json_encode(array(
                        'status' => 'File is too big',
                        'message' => 'Unfortunately, File is too big',
                    ));
                }
            }else{
                echo json_encode(array(
                    'status' => 'File might be corrupted',
                    'message' => 'Unfortunately, File might be corrupted',
                ));
            }
        }else{
            echo json_encode(array(
                'status' => 'File type is not allowed',
                'message' => 'Unfortunately, File might be corrupted',
            ));
        }
    }
}
?>