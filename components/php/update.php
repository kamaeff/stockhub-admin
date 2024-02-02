<?php
include_once("connect.php");

if (isset($_POST['updateData'])) {
	$updateData = $_POST['updateData'];

	$query = "UPDATE orders SET  
                ordered = '{$updateData['ordered']}', 
                track_value = '{$updateData['track_value']}' 
              WHERE order_id = '{$updateData['order_id']}'";

	$result = executeQuery($query);

	if ($result) {
		echo "Update successful!";
	} else {
		echo "Update failed!";
	}
}
