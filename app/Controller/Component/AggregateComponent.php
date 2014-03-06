<?php

class AggregateComponent extends Component {
	function rank($players) {

		$rank_fields = array(
			'foos_rank'
		);

		foreach($players as $key => $player) {
			$amount = 0;

			foreach($rank_fields as $field) {
				$amount += $player['Player'][$field];
			}

			$amount = $amount / count($rank_fields);
			$players[$key]['diff'] = $amount - $players[$key]['Player']['rank'];
			$players[$key]['Player']['rank'] = $amount;
		}

		return $players;

	}
}
