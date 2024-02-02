<?php
include_once("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$order_id = $_POST['order_id'];
	$ordered = $_POST['ordered'];
	$track_value = $_POST['track_value'];

	$result = executeQuery("UPDATE orders SET ordered='$ordered', track_value='$track_value' WHERE order_id='$order_id'");

	if ($result) {
		echo "success";
	} else {
		echo "error";
	}
} else {
	echo "Invalid request";
}
