<?php


use FintechFab\Catalog\Models\Category;
use FintechFab\Catalog\Models\CategoryType;

/**
 * @var integer        $id
 * @var CategoryType[] $types
 * @var Category[]     $tree
 * @var Category       $prev
 */

/**
 * @param Category $item
 * @param bool     $typed
 */
$renderItem = function ($item, $typed = false) {

	static $rendered = [];
	if (in_array($item->id, $rendered)) {
		return;
	}
	$rendered[] = $item->id;

	$weight = $typed ? 'bold' : 'normal';
	$color = $typed ? '#008cba' : 'grey';

	?>

	<tr>
		<td><?= $item->id ?></td>
		<td style="padding-left: <?= (15 * $item->level) ?>px;font-weight: <?= $weight ?>;color: <?= $color ?>"><?= e($item->name) ?></td>
		<td><?= $item->typeName() ?></td>
	</tr>

<?php


};

?>
<div class="page-header" id="banner">
	<?php require(__DIR__ . '/inc/menu.php') ?>
</div>

<div class="col-md-4">
	<h4>Type list</h4>
	<table class="table table-striped table-hover">
		<tr>
			<th>id</th>
			<th>name</th>
			<th>qnt</th>
		</tr>

		<?php

		foreach ($types as $item) {
			$weight = $id == $item->id ? 'bold' : 'normal';
			$color = $id == $item->id ? '#008cba' : 'grey';
			?>

			<tr>
				<td><?= $item->id ?></td>
				<td>
					<a href="/catalog/types/<?= $item->path ?>" style="font-weight: <?= $weight ?>;color: <?= $color ?>"><?= e($item->name) ?>
				</td>
				<td><?= $item->cnt ?></td>
			</tr>

		<?php
		}

		?>

	</table>

</div>

<div class="col-md-8">
	<h4>Tree by Type</h4>

	<table class="table table-striped table-hover">
		<tr>
			<th width="10%">id</th>
			<th width="70%">name</th>
			<th>type</th>
		</tr>

		<?php

		$rendered = [];
		foreach ($tree as $item) {

			if ($item->parent_id > 0 && !in_array($item->parent_id, $rendered)) {
				$chain = CategorySite::init($item)->parents('type');
				foreach ($chain as $parentItem) {
					$renderItem($parentItem, ($parentItem->id == $item->id));
					$rendered[] = $parentItem->id;
				}
			} else {
				$renderItem($item, true);
				$rendered[] = $item->id;
			}

		}

		?>

	</table>

</div>
