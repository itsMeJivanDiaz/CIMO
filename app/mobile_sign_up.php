<?php
require '../vendor/autoload.php';

use \Firebase\JWT\JWT;

if (isset($_POST['data'])) {

    require 'db.php';
    require 'set_token.php';
    require 'set_account.php';
    require 'set_coordinate.php';
    require 'set_address.php';
    require 'set_designated.php';

    function random_strings($length_of_string)
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result),  0, $length_of_string);
    }
    function random_ID($prefix)
    {
        return uniqid($prefix) . random_strings(11);
    }

    $stmt = mysqli_stmt_init($conn);
    $region = $_POST['region'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $brgy = $_POST['brgy'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $token = random_strings(11);
    $tokenid = random_ID('IDtkn=');
    $tokenstatus = "offline";
    $accid = random_ID('IDacc=');
    $accstats = "Approved";
    $acctype = "AcctTyID02";
    $date_time = date('Y-m-d h:i:s');
    $lat = 00.00000000000000;
    $long = 000.00000000000000;
    $coordinateid = random_ID('IDcds=');
    $desigid = random_ID('IDdsg=');
    $addressid = random_ID('IDadrs=');
    $none = "None";

    if (setToken($stmt, $tokenid, $token, $tokenstatus)) {
        if (SetAccount($stmt, $accid, $password, $username, $email, $accstats, $acctype, $tokenid, $date_time)) {
            if (SetCoordinate($stmt, $coordinateid, $lat, $long)) {
                if (SetAddress($stmt, $addressid, $region, $province, $city, $brgy, $none, $none, $coordinateid)) {
                    if (SetDesignated($stmt, $desigid, $accid, $addressid)) {
                        echo json_encode('Account creation success');
                    } else {
                        echo json_encode('Account creation failed');
                    }
                } else {
                    echo json_encode('Account creation failed');
                }
            } else {
                echo json_encode('Account creation failed');
            }
        } else {
            echo json_encode('Username might be taken');
        }
    } else {
        echo json_encode('Token creation failed');
    }
}
