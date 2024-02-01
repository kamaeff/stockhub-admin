<?php
function connect()
{
	$env_file_path = realpath(__DIR__ . "/.env");
	$var_arrs = parseEnvFile($env_file_path);

	$server = $var_arrs['HOST'];
	$db_username = $var_arrs['USER'];
	$db_password = $var_arrs['PASSWORD'];
	$dbname = $var_arrs['DATABASE'];

	$conn = new mysqli($server, $db_username, $db_password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		return $conn;
	}
}

function parseEnvFile($env_file_path)
{
	$var_arrs = [];
	$fopen = fopen($env_file_path, 'r');

	if ($fopen) {
		while (($line = fgets($fopen)) !== false) {
			$line_is_comment = (substr(trim($line), 0, 1) == '#') ? true : false;
			if ($line_is_comment || empty(trim($line)))
				continue;

			$line_no_comment = explode("#", $line, 2)[0];
			$env_ex = preg_split('/(\s?)=(\s?)/', $line_no_comment);
			$env_name = trim($env_ex[0]);
			$env_value = isset($env_ex[1]) ? trim($env_ex[1]) : "";
			$var_arrs[$env_name] = $env_value;
		}
		fclose($fopen);
	}

	return $var_arrs;
}

function executeQuery($query)
{
	$result = connect()->query($query);

	if (!$result) {
		die("Query failed: " . connect()->error);
	}

	return $result;
}

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
							header("Location: index.php");
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
