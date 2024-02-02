<?php
include_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/x-icon" href="./assets/icon/icon.ico">
	<title>AdminPanel</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="./components/style/style.css">

	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
	<script src="./app.js"></script>

</head>

<body>

	<?php
	session_start();

	if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
	?>
		<header class="header">
			<nav>
				<ul class="header__nav">
					<img src="./assets/img/stocklogo.png" width="80" height="80" alt="logo" class="header__logo me-2">
					<li><a href="#log">Логистика</a></li>
					<li><a href="#moder">Для модерации</a></li>
					<li><a href="#stat">Статистика</a></li>

				</ul>
			</nav>
			<div class="header__nav--logout">
				<img src="./assets/icon/logout.svg" width="20" height='20' alt="logout">
				<a href="logout.php" class="text-dark">Выход</a>
			</div>
		</header>

		<main class="main">

			<section class="main__users" id="stat">

				<div class="table-container">
					<table class="main__users_table">
						<!-- <caption class="main__table--title">Статистика пользователей</caption> -->
						<thead>
							<tr>
								<th></th>
								<th>ID</th>
								<th>ChatID</th>
								<th>Username</th>
								<th>Дата регистрации</th>:
								<th>Адрес доставки</th>
								<th>Email</th>
								<th>ФИО</th>
								<th>Боусы</th>
								<th>Заказы</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$page = isset($_GET['page']) ? $_GET['page'] : 1;

							$offset = ($page - 1) * 10;

							$resultCount = executeQuery("SELECT COUNT(*) as usersCount FROM users");
							$rowCount = $resultCount->fetch_assoc();
							$totalUsers = $rowCount['usersCount'];

							$totalPages = ceil($totalUsers / 10);

							echo '</div>';
							$result = executeQuery("SELECT * FROM users LIMIT 10 OFFSET $offset");

							while ($row = $result->fetch_assoc()) {
								echo '<tr>';
								echo '<td>' . ' ' . '</td>';
								echo '<td>' . $row['id'] . '</td>';
								echo '<td>' . $row['chat_id'] . '</td>';
								echo '<td>' . $row['username'] . '</td>';
								echo '<td>' . $row['data_reg'] . '</td>';
								echo '<td>' . $row['locale'] . '</td>';
								echo '<td>' . $row['email'] . '</td>';
								echo '<td>' . $row['FIO'] . '</td>';
								echo '<td>' . $row['bonus_count'] . '</td>';
								echo '<td>' . $row['orders_count'] . '</td>';
								echo '<td>' . ' ' . '</td>';
								echo '</tr>';
							}
							?>
							<caption class="main__table--title">Статистика пользователей: <?php echo $totalUsers; ?></caption>
						</tbody>
					</table>

					<div class="pagination-container ">
						<?php
						for ($i = 1; $i <= $totalPages; $i++) {
							echo '<a href="?page=' . $i . '">' . $i . '</a>';
						}
						?>
					</div>
				</div>
			</section>

			<section class="main__logist" id="log">

				<table class="main__logist_table">
					<caption class="main__table--title">Логистика</caption>
					<thead>
						<tr>
							<th>Опл/Дост</th>
							<th>ORDER_ID</th>
							<th>Стаус оплаты</th>
							<th>Email</th>
							<th>ФИО</th>
							<th>Пара</th>
							<th>Размер</th>
							<th>Цена</th>
							<th>Статус доставки</th>
							<th>Трек номер</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$result = executeQuery("SELECT * FROM orders");

						while ($row = $result->fetch_assoc()) {
							echo '<tr class="edit-mode">';

							echo '<td data-field="status-circle" class="circle-container">';
							echo '<span class="circle ' . ($row['order_status'] == 'Оплачено' ? 'order__status-succes' : 'order__status-error') . '"></span>';
							echo '<span class="circle ' . ($row['ordered'] == 'Доставлено' ? 'order__status-succes' : 'order__status-assembly') . '"></span>';
							echo '</td>';

							echo '<td data-field="order_id">' . $row['order_id'] . '</td>';

							echo '<td data-field="order_status">' . $row['order_status'] . '</td>';

							echo '<td data-field="email">' . $row['email'] . '</td>';
							echo '<td data-field="FIO">' . $row['FIO'] . '</td>';
							echo '<td data-field="name_kross">' . $row['name_kross'] . '</td>';
							echo '<td data-field="size">' . $row['size'] . '</td>';
							echo '<td data-field="price">' . $row['price'] . '</td>';

							echo '<td data-field="ordered"><input class="main__logist_table--input" type="text" class="edit-field" value="' . $row['ordered'] . '"></td>';

							echo '<td data-field="track_value"><input class="main__logist_table--input" type="text" class="edit-field" value="' . $row['track_value'] . '"></td>';

							echo '<td><button class="main__logist_table-btn--edit">Изменить</button></td>';
							echo '<td>
							<form method="POST" action="delete_order.php">
									<input type="hidden" name="order_id" value="' . $row['order_id'] . '">
									<button class="main__logist_table-btn--del" type="submit">Очистить</button>
							</form>
						</td>';
							echo '</tr>';
						}
						?>


					</tbody>
				</table>
				<div class="pagination-container pagination-left">
					<?php
					$totalPagesLogist = ceil($result->num_rows / 10);
					for ($i = 1; $i <= $totalPagesLogist; $i++) {
						echo '<a href="?page=' . $i . '">' . $i . '</a>';
					}
					?>
				</div>
			</section>

			<section class="main__moder" id="moder">
				<h2 class='text-center mb-3'>Все для модерации</h2>
				<div class="main__moder_con">
					<h3 class="main__moder_con--title">Первый шаблон</h3>
					<p class="main__moder_con--text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Expedita, quaerat et nihil officia vitae tempore iure aut impedit. Fuga quibusdam nesciunt magni voluptatum dolores praesentium officia et, similique consequuntur animi.</p>
				</div>
				<div class="main__moder_con">
					<h3 class="main__moder_con--title">Второй шаблон</h3>
					<p class="main__moder_con--text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Expedita, quaerat et nihil officia vitae tempore iure aut impedit. Fuga quibusdam nesciunt magni voluptatum dolores praesentium officia et, similique consequuntur animi.</p>
				</div>
				<div class="main__moder_con">
					<h3 class="main__moder_con--title">Третий шаблон</h3>
					<p class="main__moder_con--text">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Expedita, quaerat et nihil officia vitae tempore iure aut impedit. Fuga quibusdam nesciunt magni voluptatum dolores praesentium officia et, similique consequuntur animi.</p>
				</div>
			</section>


		</main>
	<?php
	} else {
	?>
		<form method="post" action="auth.php" class="login__form">
			<div class="container" id="loginContainer">
				<input class='log__input' type="text" placeholder="Введи логин" name="uname" required />

				<!-- <label for="psw"><b>Password</b></label> -->
				<input class='log__pass' type="password" placeholder="Введи пароль" name="psw" required />

				<div class="clearfix">
					<button class="log__btn" type="submit">Войти</button>
				</div>
			</div>
		</form>
	<?php
	}
	?>


</body>

</html>