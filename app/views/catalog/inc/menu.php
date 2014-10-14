<?php

$action = Route::current()->getActionName();
$action = explode('@', $action);
$action = end($action);

$items = [
	'edit' => [
		'title' => 'TreeView',
		'url'   => '/ff-cat/',
	],
	'index' => [
		'title' => 'Tree',
		'url'   => '/catalog/',
	],
	'tags'  => [
		'title' => 'Tags',
		'url'   => '/catalog/tags',
	],
	'types' => [
		'title' => 'Types',
		'url'   => '/catalog/types',
	],
	'margins' => [
		'title' => 'Margins',
		'url'   => '/catalog/margins',
	],
];

?><h3 style="display: inline;margin-right: 20px;">fintech-fab/catalog:</h3><?php

foreach ($items as $key => $item) {
	if ($action == $key) {
		?><h4 style="display: inline;margin-right: 20px;"><?= $item['title'] ?></h4><?php
	} else {
		?><h4 style="display: inline;margin-right: 20px;"><a href="<?= $item['url'] ?>"><?= $item['title'] ?></a>
		</h4><?php
	}
}
 