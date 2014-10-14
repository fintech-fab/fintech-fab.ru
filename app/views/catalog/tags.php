<?php


use FintechFab\Catalog\Models\Category;
use FintechFab\Catalog\Models\CategoryTag;

/**
 * @var Category[]    $tree
 * @var Category      $prev
 * @var CategoryTag[] $tags
 * @var integer       $id
 */


/**
 * @param CategoryTag[] $tags
 */
$showTags = function ($tags) {
	foreach ($tags as $key => $tag) {
		echo e($tag->name);
		if ($key < count($tags) - 1) {
			echo ", ";
		}
	}
};

/**
 * @param Category $item
 * @param bool     $tagged
 * @param          $showTags
 */
$renderItem = function ($item, $tagged = false, $showTags) {

	static $rendered = [];
	if (in_array($item->id, $rendered)) {
		return;
	}
	$rendered[] = $item->id;

	$weight = $tagged ? 'bold' : 'normal';
	$color = $tagged ? '#008cba' : 'grey';

	?>

	<tr>
		<td><?= $item->id ?></td>
		<td style="padding-left: <?= (15 * $item->level) ?>px;font-weight: <?= $weight ?>;color: <?= $color ?>"><?= e($item->name) ?></td>
		<td><?= $showTags($item->tags) ?></td>
	</tr>

<?php


};

?>
<div class="page-header" id="banner">
	<?php require(__DIR__ . '/inc/menu.php') ?>
</div>

<div class="col-md-4">
	<h4>Tag list</h4>
	<table class="table table-striped table-hover">
		<tr>
			<th>id</th>
			<th>name</th>
			<th>qnt</th>
		</tr>

		<?php

		foreach ($tags as $item) {
			$weight = $id == $item->id ? 'bold' : 'normal';
			$color = $id == $item->id ? '#008cba' : 'grey';
			?>

			<tr>
				<td><?= $item->id ?></td>
				<td>
					<a href="/catalog/tags/<?= $item->path ?>" style="font-weight: <?= $weight ?>;color: <?= $color ?>"><?= e($item->name) ?>
				</td>
				<td><?= $item->cnt ?></td>
			</tr>

		<?php
		}

		?>

	</table>

</div>

<div class="col-md-8">
	<h4>Tree by Tag</h4>

	<table class="table table-striped table-hover">
		<tr>
			<th width="10%">id</th>
			<th width="50%">name</th>
			<th>tags</th>
		</tr>

		<?php

		foreach ($tree as $item) {

			if ($item->parent_id > 0) {
				$chain = CategorySite::init($item)->parents('tags');
				foreach ($chain as $parentItem) {
					$renderItem($parentItem, ($parentItem->id == $item->id), $showTags);
				}
			} else {
				$renderItem($item, true, $showTags);
			}

		}

		?>

	</table>

</div>
