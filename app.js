$(document).ready(function () {
	$('.main__logist_table-btn--edit').click(function () {
		var row = $(this).closest('tr')

		var ordered = row.find('[data-field="ordered"] input').val()
		var track_value = row.find('[data-field="track_value"] input').val()
		var order_id = row.find('[data-field="order_id"]').text()

		$.ajax({
			url: 'update.php',
			type: 'POST',
			data: {
				order_id: order_id,
				ordered: ordered,
				track_value: track_value,
			},
			success: function (response) {
				if (response === 'success') {
					alert(
						`Изменения для ${order_id} сохранены\n${
							'Статус доставки: ' + ordered
						}\n${'Трек номер: ' + track_value}`
					)
				} else {
					alert('Ошибка при сохранении изменений')
				}
			},
			error: function () {
				alert('Ошибка при отправке запроса')
			},
		})
	})
})
