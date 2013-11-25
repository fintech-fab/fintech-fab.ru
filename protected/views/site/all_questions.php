<?php
/* @var $this SiteController */
/* @var $model FaqGroup[] */
/* @var $oGroup FaqGroup */

?>


<table id="faq_table">
	<tbody>
	<?php
	foreach ($model as $oGroup) {
		?>
		<tr>
			<td valign="top">
				<strong><?= $oGroup->title ?></strong>
			</td>
			<td>
				<?php foreach ($oGroup->questions as $oQuestion) {
					$collapse = $this->beginWidget('bootstrap.widgets.TbCollapse', array('id' => 'questions' . $oQuestion->id));
					?>

					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?= $oQuestion->id ?>">
								<?= $oQuestion->title ?> </a>
						</div>
						<div id="collapse<?= $oQuestion->id ?>" class="accordion-body collapse">
							<div class="accordion-inner">
								<?= $oQuestion->answer ?>
							</div>
						</div>
					</div>

					<?php $this->endWidget();
				} ?>
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
