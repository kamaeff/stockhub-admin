<?php
include_once("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$title = $_POST['title'];
	$brand = $_POST['brand'];
	$mat = $_POST['mat'];
	$color = $_POST['color'];
	$art = $_POST['art'];
	$size = $_POST['size'];
	$photo = $_POST['photo'];
	$price = $_POST['price'];
	$flag_order = $_POST['flag_order'];
	$gender = $_POST['gender'];
	$style = $_POST['style'];

	$result = executeQuery("INSERT INTO Updates (name, name_shooes, material, color, artilul, size, photo_path,  price, flag_order, gender_option, style) VALUES ('$title', '$brand', '$mat', '$color', '$art', '$size', '$photo', '$price', '$flag_order', '$gender', '$style')");

	if ($result) {
		header("Location: ./../../index.php");
		echo "Запись успешно добавлена";
	} else {
		echo "error";
	}
} else {
	echo "Invalid request";
}
