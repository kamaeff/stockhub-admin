<?php
include_once("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$order_id = $_POST['order_id'];

	$name_result = executeQuery("SELECT name_kross FROM orders WHERE order_id = '$order_id'");

	if ($name_result) {
		$row = $name_result->fetch_assoc();

		if (isset($row['name_kross'])) {
			$name_kross = $row['name_kross'];
			$delete_order_query = executeQuery("DELETE FROM orders WHERE order_id = '$order_id'");

			$update_flag_order = executeQuery("UPDATE Updates SET flag_order = 0 WHERE name = '$name_kross'");

			if ($delete_order_query && $update_flag_order) {
				header("Location: ./../../index.php");
				// header("Refresh: 5");
				exit();
			} else {
				echo "Error deleting order or updating flag_order.";
			}
		} else {
			echo "Error: 'name_kross' not found.";
		}
	} else {
		echo "Error retrieving 'name_kross' from orders table.";
	}
} else {
	header("Location: error_page.php");
	exit();
}
