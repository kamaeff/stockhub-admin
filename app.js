function toggleStatSection(sectionId) {
	var section = document.getElementById(sectionId)
	section.classList.toggle('block')
}

document.addEventListener('DOMContentLoaded', function () {
	var statLink = document.getElementById('statLink')

	statLink.addEventListener('click', function () {
		toggleStatSection('stat')
		handleEditButtonClick()
	})

	handleEditButtonClick()
})

function handleEditButtonClick() {
	$('.edit-btn').on('click', function () {
		var row = $(this).closest('tr')
		var fields = row.find('[data-field]')

		var data = {}
		fields.each(function () {
			var field = $(this).data('field')
			var value

			if ($(this).children('.edit-field').length) {
				value = $(this).children('.edit-field').val()
			} else {
				value = $(this).text()
			}

			data[field] = value
		})

		$.ajax({
			type: 'POST',
			url: 'update.php',
			data: { updateData: data },
			success: function (response) {
				console.log(response)
			},
		})
	})
}
