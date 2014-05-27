<?php

$filters = array();

$filters[] = function ($element, $index) {
	$element['id'] = $index;

	return $element;
};

foreach($filters as $filter) {
	$games = array_map($filter, $games, array_keys($games));
}

$games = array_values($games);

echo json_encode($games);