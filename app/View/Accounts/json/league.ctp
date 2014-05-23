<?php

$filters = array();

$filters[] = function ($item) {
	$item['record']['wins'] = intval($item['record']['wins']);
	$item['record']['loss'] = intval($item['record']['loss']);

	$item['players']['record'] = $item['record'];
	unset($item['record']);

	return $item;
};

$filters[] = function($item) {
	return $item['players'];
};

$filters[] = function($item) {
	$remove = array(
		'account_id',
		'foos_rank',
		'foos_performance_rank',
		'elo_rank'
	);

	foreach($remove as $field) {
		unset($item[$field]);
	}

	return $item;
};

$players = array_merge($players, $unranked);


foreach($filters as $filter) {
	$players = array_map($filter, $players);
}

echo json_encode($players);