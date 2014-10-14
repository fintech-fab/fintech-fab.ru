<?php


use FintechFab\Catalog\Models\Category;

/**
 * @var integer    $id
 * @var Category[] $tree
 * @var Category[] $treeMargins
 * @var Category[] $treeParents
 * @var Category[] $neighbors
 */

?>
<div class="page-header" id="banner">
	<?php require(__DIR__ . '/inc/menu.php') ?>
</div>

<div class="col-md-5">
	<table class="table table-striped table-hover">
		<tr>
			<th>id</th>
			<th>name</th>
			<th>left</th>
			<th>right</th>
		</tr>

		<?php

		foreach ($tree as $item) {

			$weight = $id == $item->id ? 'bold' : 'normal';
			$color = $id == $item->id ? '#008cba' : 'grey';

			?>
			<tr>
				<td><?= $item->id ?></td>
				<td style="padding-left: <?= (15 * $item->level) ?>px;">
					<a href="/catalog/margins/<?= $item->id ?>" style="color:<?= $color ?>;font-weight:<?= $weight ?>;"><?= e($item->name) ?></a>
				</td>
				<td><?= $item->left ?></td>
				<td><?= $item->right ?></td>
			</tr>

		<?php
		}

		?>

	</table>

</div>

<div class="col-md-1"></div>

<div class="col-md-6">

	<h4>Parent chain</h4>

	<table class="table table-striped table-hover">
		<tr>
			<th width="10%">id</th>
			<th>name</th>
			<th width="10%">left</th>
			<th width="10%">right</th>
		</tr>

		<?php

		foreach ($treeParents as $item) {
			?>
			<tr>
				<td><?= $item->id ?></td>
				<td style="padding-left: <?= (15 * $item->level) ?>px;"><?= e($item->name) ?></td>
				<td><?= $item->left ?></td>
				<td><?= $item->right ?></td>
			</tr>

		<?php
		}

		?>

	</table>

	<h4>Descendants</h4>

	<table class="table table-striped table-hover">
		<tr>
			<th width="10%">id</th>
			<th>name</th>
			<th width="10%">left</th>
			<th width="10%">right</th>
		</tr>

		<?php

		foreach ($treeMargins as $item) {
			?>
			<tr>
				<td><?= $item->id ?></td>
				<td style="padding-left: <?= (15 * $item->level) ?>px;"><?= e($item->name) ?></td>
				<td><?= $item->left ?></td>
				<td><?= $item->right ?></td>
			</tr>

		<?php
		}

		?>

	</table>

	<h4>Neighbors</h4>

	<table class="table table-striped table-hover">
		<tr>
			<th width="10%">id</th>
			<th>name</th>
			<th width="10%">left</th>
			<th width="10%">right</th>
		</tr>

		<?php

		foreach ($neighbors as $item) {
			?>
			<tr>
				<td><?= $item->id ?></td>
				<td style="padding-left: <?= (15 * $item->level) ?>px;"><?= e($item->name) ?></td>
				<td><?= $item->left ?></td>
				<td><?= $item->right ?></td>
			</tr>

		<?php
		}

		?>

	</table>

</div>