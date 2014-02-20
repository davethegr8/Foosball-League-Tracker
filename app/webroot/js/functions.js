$(function () {
	$('.score-minus').on('click', function () {
		var $input = $(this).closest('td').find('input'),
			value = $input.val() * 1;

		if(value > 0) {
			value--;
		}

		$input.val(value);
	});

	$('.score-plus').on('click', function () {
		var $input = $(this).closest('td').find('input'),
			value = $input.val() * 1;

		value++;

		$input.val(value);
	});
});