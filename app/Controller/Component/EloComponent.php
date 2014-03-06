<?php

class EloComponent extends Component {
	function rank($players, $side1_score, $side2_score, array $options = array()) {
		//** SETTINGS **//
		$defaults = array(
			'field' => 'elo_rank'
		);
		$options = array_merge($defaults, $options);

		$ranks = array(
			1 => 0,
			2 => 0
		);

		foreach($players as $player) {
			$ranks[$player['side']] += $player['Player'][$options['field']];
		}

		$k = (array_sum($ranks) > 4200 ? 16 : 32);

		$expected[1] = 1 / (1 + pow(10, (($ranks[2] - $ranks[1]) / 400)));
		$expected[2] = 1 / (1 + pow(10, (($ranks[1] - $ranks[2]) / 400)));

		if($side1_score > $side2_score) {
			$score[1] = 1;
			$score[2] = 0;
		}
		elseif($side1_score < $side2_score) {
			$score[1] = 0;
			$score[2] = 1;
		}
		else {
			$score[1] = .5;
			$score[2] = .5;
		}

		$points[1] = $k * ($score[1] - $expected[1]);
		$points[2] = $k * ($score[2] - $expected[2]);

		foreach($players as $key => $player) {
			$players[$key]['Player'][$options['field']] += $points[$player['side']];
			$players[$key]['diff'] = $points[$player['side']];
		}

		return $players;
	}
}
