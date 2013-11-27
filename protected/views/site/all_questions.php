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
			<td valign="top" class="span2">
				<strong><?= $oGroup->title ?></strong>
			</td>
			<td>
				<?php foreach ($oGroup->questions as $oQuestion) {
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


				<?php } ?>
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>

<script>
	function openQuestionByAnchor(anchor) {
		jQuery(anchor).removeClass('collapse');
	}

	jQuery("div.accordion-inner a[href ^= '#collapse']").click(function () {
		openQuestionByAnchor(jQuery(this).attr('href'));
	});
</script>

<?php

Yii::app()->clientScript->registerScript('openQuestionByAnchor', "
var sHash = $(location).attr('hash');
if(typeof sHash!='undefined' && sHash.length){
   openQuestionByAnchor(jQuery(sHash));
}
", CClientScript::POS_READY);
?>
