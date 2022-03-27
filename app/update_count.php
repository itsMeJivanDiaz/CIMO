<?php
if(isset($_POST['count_id'])){

    require 'db.php';

    $count_id = $_POST['count_id'];
    $current_count = $_POST['current_count'];
    $starter = $_POST['counter'];
    $sql = "UPDATE count SET CurrentCount = ?, Counter = ? WHERE CountID = ?;";

    $stmt = mysqli_stmt_init($conn);

    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, 'iis', $current_count, $starter, $count_id);
    mysqli_stmt_execute($stmt);
}
?>