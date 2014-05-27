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

	(function () {
		var fetchPlayers,
			playerData;

		$('#mass-games-input').on('keyup', function () {
			var data = $(this).val().split("\n");

			if(playerData === undefined && fetchPlayers === undefined) {
				fetchPlayers = $.ajax('/accounts/league.json').done(function (data) {
					playerData = data;
				});
			}

			data = data.map(function (element, index) {
				if(!element.replace(/\s/, '')) {
					return;
				}

				var game = element.split('|'),
					side1Raw = game[0].split(','),
					side2Raw = game[1].split(','),
					side1 = {},
					side2 = {},
					output = '<p><span class="side1 {side1Classes}">{side1}</span>vs<span class="side2 {side2Classes}">{side2}</span></p>',
					context = {
						side1: "s1",
						side1Classes: "",
						side2: "s2",
						side2Classes: ""
					},
					interpolate = function (message, context) {
						for(var key in context) {
							message = message.split('{' + key + '}').join(context[key]);
						}

						return message;
					};

				side1Raw.forEach(function(element, index) {
					if(element * 1 == element) {
						side1.score = element * 1;
					}
				});

				side2Raw.forEach(function(element, index) {
					if(element * 1 == element) {
						side2.score = element * 1;
					}
				});

				if(side1.score > side2.score) {
					context.side1Classes += ' win';
				}
				else  if(side2.score > side1.score) {
					context.side2Classes += ' win';
				}

				output = interpolate(output, context);

				console.log(context);

				return output;
			});

			$('#mass-games-preview').html(data);
		});
	})();

});