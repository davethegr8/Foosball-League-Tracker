<?php

$filters = array();

$filters[] = function (&$element, $index) {
	$element['id'] = $index;
};

foreach($filters as $filter) {
	array_walk($games, $filter);
}

$games = array_values($games);

echo json_encode($games);