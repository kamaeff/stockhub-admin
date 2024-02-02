<?php
function connect()
{
	$env_file_path = realpath("D:\OSPanel\domains\stockhub-admin\.env");
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
