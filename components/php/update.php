<?php
include_once("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$order_id = $_POST['order_id'];

	$ordered = $_POST['ordered'];
	$track_value = $_POST['pole2'];

	$result = executeQuery("UPDATE orders SET ordered='$ordered', track_value='$track_value' WHERE order_id='$order_id'");

	if ($result) {
		header("Location: ./../../index.php");
		echo "success";
	} else {
		echo "error";
	}
} else {
	echo "Invalid request";
}
