$(function () {
	$('table.sort').tablesorter();

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
			playerData,
			gamesAdding = [];

		$('#adder').on('click', function () {
			fetchPlayers = $.ajax('/accounts/league.json').done(function (data) {
				console.log('league fetched');
				playerData = data;
			});

			$('#mass-adder').show();
		})

		$('#mass-adder input[value="Close"]').on('click', function () {
			$('#mass-adder').hide();
		});


		$('#mass-adder').detach().insertAfter('#top');

		var parseGames = function () {
			var data = $('#mass-games-input').val().split("\n");

			if(playerData === undefined) {
				return;
			}

			gamesAdding = [];

			data = data.map(function (element, index) {
				if(!element.replace(/\s/, '')) {
					return;
				}

				var game = element.split('|'),
					side1Raw = game[0].split(','),
					side2Raw = game[1].split(','),
					side1 = {
						score: 0,
						players: []
					},
					side2 = {
						score: 0,
						players: []
					},
					output = '<p><span class="index">{index}.</span><span class="side1 {side1Classes}">{side1}: {side1Score}</span>vs<span class="side2 {side2Classes}">{side2}: {side2Score}</span></p>',
					context = {
						index: 0,
						side1: "s1",
						side1Classes: [],
						side1Score: 0,
						side2: "s2",
						side2Classes: [],
						side2Score: 0
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
						context.side1Score = side1.score;
					}
					else {
						side1.players.push({ raw: element} );
					}
				});

				side2Raw.forEach(function(element, index) {
					if(element * 1 == element) {
						side2.score = element * 1;
						context.side2Score = side2.score;
					}
					else {
						side2.players.push({ raw: element} );
					}
				});

				side1.players.forEach(function(element, index) {
					var raw = element.raw,
						matches = [];

					playerData.forEach(function (player) {
						var query = raw.toLowerCase().split(''),
							found = true,
							index,
							playerName = player.name.toLowerCase();


						for(var i=0;i<query.length;i++) {
							if((index = playerName.indexOf(query[i])) == -1) {
								found = false;
								break;
							}

							playerName = playerName.slice(0, index) + playerName.slice(index + 1, playerName.length);
						}

						if(found) {
							matches.push(player);
						}
					});

					if(matches.length == 1) {
						side1.players[index] = matches[0];
					}
					else {
						side1.players[index] = { name: "??? (" + raw + ")" };
					}
				});

				side2.players.forEach(function(element, index) {
					var raw = element.raw,
						matches = [];

					playerData.forEach(function (player) {
						var query = raw.toLowerCase().split(''),
							found = true,
							index,
							playerName = player.name.toLowerCase();


						for(var i=0;i<query.length;i++) {
							if((index = playerName.indexOf(query[i])) == -1) {
								found = false;
								break;
							}

							playerName = playerName.slice(0, index) + playerName.slice(index + 1, playerName.length);
						}

						if(found) {
							matches.push(player);
						}
					});

					if(matches.length == 1) {
						side2.players[index] = matches[0];
					}
					else {
						side2.players[index] = { name: "??? (" + raw + ")" };
					}
				});

				context.side1 = side1.players.map(function (element) {
					return element.name;
				}).join(', ');

				context.side2 = side2.players.map(function (element) {
					return element.name;
				}).join(', ');

				if(side1.score > side2.score) {
					context.side1Classes.push('win');
				}
				else if(side2.score > side1.score) {
					context.side2Classes.push('win');
				}

				context.side1Classes = context.side1Classes.join(' ');
				context.side2Classes = context.side2Classes.join(' ');

				context.index = index + 1;

				output = interpolate(output, context);

				gamesAdding.push({
					"side1": side1,
					"side2": side2
				});

				return output;
			});

			$('#mass-games-data').val(JSON.stringify(gamesAdding));
			$('#mass-games-preview').html(data);
		};

		$('#mass-games-input').on('keyup', parseGames);

		$('#mass-form').on('submit', function (event) {
			parseGames();
			$('#mass-games-data').val(JSON.stringify(gamesAdding));
		});
	})();

});
