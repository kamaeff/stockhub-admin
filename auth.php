<?php
session_start();

function connect()
{
	$env_file_path = realpath(__DIR__ . "/.env");
	$var_arrs = array();
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
	
	$server = $var_arrs['HOST'];
	$db_username = $var_arrs['USER'];
	$db_password =  $var_arrs['PASSWORD'];
	$dbname = $var_arrs['DATABASE'];

	$conn = new mysqli($server, $db_username, $db_password, $dbname);

	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} else {
		echo "<script>console.log('Connection succes')</script>";
		return $conn;
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$input_username = $_POST['uname'];
	$input_password = $_POST['psw'];

	$result = connect()->query("SELECT * FROM adm WHERE uname = '$input_username' AND psw = '$input_password'");

	if ($result !== false && $result->num_rows > 0) {
		$_SESSION['authenticated'] = true;
		echo "<script>alert('Успешно');</script>";
		header("Location: index.php");
		exit;
	} else {
		echo "<script>alert('Неверный логин или пароль. Пожалуйста, попробуйте еще раз.');</script>";
		echo "<script>window.location.href = 'index.php';</script>";
	}

	$conn->close();
}
