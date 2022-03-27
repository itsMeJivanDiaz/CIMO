<?php

if(isset($_POST['cap_id'])){

    require 'db.php';
    $cap_id = $_POST['cap_id'];

    $sql = "SELECT * FROM capacity WHERE CapacityID = ?;";
    $stmt = mysqli_stmt_init($conn);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 's', $cap_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    echo json_encode(array(
        'normal' => $row['NormalCapacity'],
        'limit' => $row['LimitedCapacity'],
    ));

}

?>