<?php
session_start();

include_once("./components/php/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$input_username = $_POST['uname'];
	$input_password = md5($_POST['psw']);

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
