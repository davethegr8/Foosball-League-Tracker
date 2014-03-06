<?php

class FoosRankComponent extends Component {

	/**
	 * [rank description]
	 *
	 * @return [type]          [description]
	 */
	function rank($players, $side1_score, $side2_score, array $options = array()) {
		//** SETTINGS **//
		$defaults = array(
			//the amount of points you get for playing. Makes rank have an overall upwards trend
			//makes it kinda worthwhile to play, even if you suck
			'participation_points' => 3,

			//how important the difference in goals is. 1 = not important.
			//setting higher makes larger score differences give more points.
			'goal_diff_multiplier' => 1,

			//how many points you get for winning
			'win_points' => 10,

			//how many points is the minimum for winning
			'win_min_points' => 0,

			//how many points = expected goal diff of one
			'one_goal_diff' => 100,

			//check a specific field for rank values
			'field' => 'foos_rank'
		);
		$options = array_merge($defaults, $options);

		$ranks = array(
			1 => 0,
			2 => 0
		);

		foreach($players as $player) {
			$ranks[$player['side']] += $player['Player'][$options['field']];
		}

		$expected_diff[1] = ceil( ($ranks[1] - $ranks[2]) / $options['one_goal_diff']);
		$expected_diff[2] = ceil( ($ranks[2] - $ranks[1]) / $options['one_goal_diff']);

		$actual_diff[1] = $side1_score - $side2_score;
		$actual_diff[2] = $side2_score - $side1_score;

		$final_diff[1] = $actual_diff[1] - $expected_diff[1];
		$final_diff[2] = $actual_diff[2] - $expected_diff[2];

		$points[1] = $final_diff[1] * $options['goal_diff_multiplier'] + $options['participation_points'];
		$points[2] = $final_diff[2] * $options['goal_diff_multiplier'] + $options['participation_points'];

		if ($side1_score > $side2_score) {
			$points[1] += $options['win_points'];

			if (is_int($options['win_min_points'])) {
				$points[1] = max($points[1], $options['win_min_points']);
			}

			$points[2] -= $options['win_points'];
		} elseif ($side1_score < $side2_score) {
			$points[2] += $options['win_points'];

			if (is_int($options['win_min_points'])) {
				$points[2] = max($points[2], $options['win_min_points']);
			}

			$points[1] -= $options['win_points'];
		} else {
			//ties don't happen
		}

		foreach($players as $key => $player) {
			$players[$key]['Player'][$options['field']] += $points[$player['side']];
			$players[$key]['diff'] = $points[$player['side']];
		}

		return $players;
	}
}
