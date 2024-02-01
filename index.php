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

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>AdminPanel</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
	<link rel="stylesheet" href="./components/style/style.css">

	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="./app.js"></script>

</head>

<body>

	<?php
	session_start();

	if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
	?>
		<header class="header">
			<img src="./assets/img/stocklogo.png" width="80" height="80" alt="logo" class="header__logo">
			<nav>
				<ul class="header__nav">

					<li><a href="#" id="logLink">Логистика</a></li>
					<li><a href="#sup">Для модерации</a></li>
					<li><a href="#" id="statLink">Статистика</a></li>
					<li class="header__nav--logout">
						<img src="./assets/icon/logout.svg" width="20" height='20' alt="logout">
						<a href="logout.php">Выход</a>
					</li>
				</ul>
			</nav>
		</header>

		<main class="main">
			<section class="main__logist" id="log">
				<!-- todo: сделать таблицы заказов -->

				<table class="main__logist_table">
					<thead>
						<tr>
							<th>ORDER_ID</th>
							<th>Стаус оплаты</th>
							<th>Email</th>
							<th>ФИО</th>
							<th>Пара</th>
							<th>Размер</th>
							<th>Цена</th>
							<th>Статус доставки</th>
							<th>Трек номер</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$result = executeQuery("SELECT * FROM orders");

						while ($row = $result->fetch_assoc()) {
							echo '<tr class="edit-mode">';
							echo '<td data-field="order_id">' . $row['order_id'] . '</td>';
							echo '<td data-field="order_status">' . $row['order_status'] . '</td>';
							echo '<td data-field="email">' . $row['email'] . '</td>';
							echo '<td data-field="FIO">' . $row['FIO'] . '</td>';
							echo '<td data-field="name_kross">' . $row['name_kross'] . '</td>';
							echo '<td data-field="size">' . $row['size'] . '</td>';
							echo '<td data-field="price">' . $row['price'] . '</td>';

							echo '<td data-field="ordered"><input type="text" class="edit-field" value="' . $row['ordered'] . '"></td>';

							echo '<td data-field="track_value"><input type="text" class="edit-field" value="' . $row['track_value'] . '"></td>';

							echo '<td><button class="edit-btn">Edit</button></td>';
							echo '<td>
							<form method="POST" action="delete_order.php">
									<input type="hidden" name="order_id" value="' . $row['order_id'] . '">
									<button type="submit" class="delete-btn">Delete</button>
							</form>
						</td>';
							echo '</tr>';
						}
						?>

					</tbody>
				</table>
			</section>

			<section class="main__users" id="stat">

				<div class="main__user_stat">
					<?php
					$result = executeQuery("SELECT COUNT(*) as usersCount FROM users");
					while ($row = $result->fetch_assoc()) {
						echo '<p>' . '<span style="font-weight: 500; font-size: 20px">Users: </span>' . $row['usersCount'] . '</p>';
					}

					?>
				</div>
				<table class="main__users_table">
					<thead>
						<tr>
							<th>ID</th>
							<th>ChatID</th>
							<th>Username</th>
							<th>Registration</th>
							<th>Location</th>
							<th>Email</th>
							<th>FIO</th>
						</tr>
					</thead>
					<tbody>

						<?php
						$result = executeQuery("SELECT * FROM users");
						while ($row = $result->fetch_assoc()) {
							echo '<tr>';
							echo '<td>' . $row['id'] . '</td>';
							echo '<td>' . $row['chat_id'] . '</td>';
							echo '<td>' . $row['username'] . '</td>';
							echo '<td>' . $row['data_reg'] . '</td>';
							echo '<td>' . $row['locale'] . '</td>';
							echo '<td>' . $row['email'] . '</td>';
							echo '<td>' . $row['FIO'] . '</td>';

							echo '</tr>';
						}
						?>

					</tbody>
				</table>

			</section>
		</main>
	<?php
	} else {
	?>
		<form method="post" action="auth.php" class="login__form">
			<div class="container" id="loginContainer">
				<!-- <label for="uname"><b>Username</b></label> -->
				<input type="text" placeholder="Введи логин" name="uname" required />

				<!-- <label for="psw"><b>Password</b></label> -->
				<input type="password" placeholder="Введи пароль" name="psw" required />

				<div class="clearfix">
					<button type="submit">Войти</button>
				</div>
			</div>
		</form>
	<?php
	}
	?>


</body>

</html>