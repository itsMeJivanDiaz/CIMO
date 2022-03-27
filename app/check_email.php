<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
if(isset($_POST['email-send'])){
    require 'db.php';
    $email = $_POST['email-send'];
    $sql = "SELECT * FROM account WHERE Email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo json_encode(array(
            'status' => 'Error',
            'message' => 'Something went wrong #1',
        ));
    }else{
        mysqli_stmt_bind_param($stmt, 's', $email);
        if(!mysqli_stmt_execute($stmt)){
            echo json_encode(array(
                'status' => 'Error',
                'message' => 'Something went wrong #2',
            ));
        }else{
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            if($row <= 0){
                echo json_encode(array(
                    'status' => 'Error',
                    'message' => 'Email not found!',
                ));
            }else{
                $tokenid = $row['TokenID'];
                $get_jwt = "SELECT * FROM authentication_token WHERE TokenID = ?;";
                if(!mysqli_stmt_prepare($stmt, $get_jwt)){
                    echo json_encode(array(
                        'status' => 'Error',
                        'message' => 'Token Error',
                    ));
                }else{
                    mysqli_stmt_bind_param($stmt, 's', $tokenid);
                    if(!mysqli_stmt_execute($stmt)){
                        echo json_encode(array(
                            'status' => 'Error',
                            'message' => 'Token Error',
                        ));
                    }else{
                        $res = mysqli_stmt_get_result($stmt);
                        $row_result = mysqli_fetch_assoc($res);
                        $mail = new PHPMailer(true);
                        $mail->isSMTP();
                        $mail->SMTPAuth   = TRUE;
                        $mail->Host = "smtp.gmail.com";
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = "587";
                        $mail->oauthUserEmail = "jivangdiazchmsc3b@gmail.com";
                        $mail->oauthClientId = "317085498520-kgbbalem6htr2n5uhr6bad7pmvbsps5b.apps.googleusercontent.com";
                        $mail->oauthClientSecret = "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";
                        $mail->oauthRefreshToken = "1//0ecm8ft58xiblCgYIARAAGA4SNwF-L9IrddVUX0Mzml_Rhq-48JaWFrhB2--gcDIIYJqY9Fh3kcSPA5fAjceK5lic-LDLKLGrzag";
                        $mail->Username = "jivangdiazchmsc3b@gmail.com";
                        $mail->Password = "balanar202";
                        $mail->isHTML(true);
                        $mail->Subject = "CIMO reset password";
                        $mail->setFrom("jivangdiazchmsc3b@gmail.com", "CIMO TECH");
                        $mail->Body = "<h2>Hello, This is the CIMO Tech </h2> <p>Please copy your token below and paste it to the verification input.<p/><p>".$row['TokenID']."</p>";
                        $mail->addAddress($row['Email']);
                        $status = $mail->send();
                        if($status){
                            $mail->smtpClose();
                            echo json_encode(array(
                                'status' => 'Success',
                                'message' => 'Verification sent!',
                            ));
                        }else{
                            $mail->smtpClose();
                            echo json_encode(array(
                                'status' => 'Success',
                                'message' => 'Ooops! Error',
                            ));
                        }
                    }
                }
            }
        }
    }
}
?>