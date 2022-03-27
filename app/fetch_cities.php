<?php

require 'db.php';

header('Content-Type:application/json');

$stmt = mysqli_stmt_init($conn);

if(isset($_GET['cities'])){

	$sql = "SELECT * FROM address GROUP by City ORDER BY City ASC;";

	$json_data = array();

	if(!mysqli_stmt_prepare($stmt, $sql)){
		echo json_encode(array(
			'Response_message' => 'Something went wrong'
		));
	}else{
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row = mysqli_fetch_assoc($result)){
			$city = $row['City'];
			if(strpos($city, 'CITY') == false){
				$city = $row['City'] . ' MUNICIPALITY';
			}
			$array = array(
				'city' => $city,
			);
			array_push($json_data,  $array);
		}
	}
	echo json_encode($json_data, JSON_PRETTY_PRINT);
}

?>