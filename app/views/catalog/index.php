<?php


use FintechFab\Catalog\Models\Category;
use FintechFab\Catalog\Models\CategoryTag;

/**
 * @var Category[] $tree
 */



/**
 * @param CategoryTag[] $tags
 */
$showTags = function ($tags) {
	foreach ($tags as $key => $tag) {
		?><a href="/catalog/tags/<?= $tag->path ?>"><?= e($tag->name) ?></a><?php
		if ($key < count($tags) - 1) {
			echo ", ";
		}
	}
};

?>
<div class="page-header" id="banner">
	<?php require(__DIR__ . '/inc/menu.php') ?>
</div>

<div class="col-md-12">
	<table class="table table-striped table-hover">
		<tr>
			<th>id</th>
			<th>name</th>
			<th>type</th>
			<th>tags</th>
			<th>symlink</th>
			<th>left</th>
			<th>right</th>
			<th>level</th>
		</tr>

		<?php

		foreach ($tree as $item) {
			?>
			<tr>
				<td><?= $item->id ?></td>
				<td style="padding-left: <?= (15 * $item->level) ?>px;"><?= e($item->name) ?></td>
				<td><?= $item->typeName() ?></td>
				<td><?= $showTags($item->tags) ?></td>
				<td><?= $item->symlink ? '[' . $item->symlink_id . '] ' . $item->symlink->name : '' ?></td>
				<td><?= $item->left ?></td>
				<td><?= $item->right ?></td>
				<td><?= $item->level ?></td>
			</tr>

		<?php
		}

		?>

	</table>

</div>