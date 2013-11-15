<?php

/**
 * @var $this UserCityWidget
 * @var $sDataContent
 * @var $sModalBody
 */
?>
<?php
/**
 * Рисуем тэг с ID виджеьа только если виджет не обновляется, а рисуется новый.
 * Для обновления виджета тэг не нужен - он уже есть на странице и в него грузится обновленный виджет.
 */
if (!$this->bUpdate):
?>
<div id="userCityWidget">
	<?php endif; ?>

	<?php
	$this->widget(
		'bootstrap.widgets.TbButton',
		array(
			'id'          => 'userLocation',
			'label'       => $this->sCityName . '<i class="caret-white"></i>',
			'type'        => 'link',
			'encodeLabel' => false,
			'htmlOptions' => array(
				'class' => 'location'
			)
		)
	);
	?>
	<script type="text/javascript">
		var userLocation = $('#userLocation');
		userLocation.popover({
			'selector': '',
			'placement': 'bottom',
			'content': '<?= $sDataContent; ?>',
			'html': 'true'
		});
		<?php if(!$this->bCitySelected): ?>
		userLocation.popover('show');
		<?php endif ?>

		function confirmCity() {
			var cityName = '<?= $this->sCityName ?>';
			var cityAndRegion = '<?= $this->sCityAndRegion ?>';
			$.ajax({
				url: "<?= Yii::app()->createUrl('/site/setCityToCookie') ?>",
				type: "POST",
				cache: false,
				dataType: "html",
				data: ({
					'cityName': cityName,
					'cityAndRegion': cityAndRegion,
					'<?= $this->sCsrfTokenName ?>': "<?= $this->sCsrfToken ?>"
				}),
				success: function (html) {
					$("#userCityWidget").html(html);
				}
			}).done(function () {
					var host = $(location).attr("hostname");
					if (cityAndRegion.match(/Ивановская область/i) && !host.match(/ivanovo/i)) {
						window.location.href = "<?= Yii::app()->params['ivanovoUrl'] ?>";
					}
					if (!cityAndRegion.match(/Ивановская область/i) && host.match(/ivanovo/i)) {
						window.location.href = "<?= Yii::app()->params['mainUrl'] ?>";
					}
				});
			userLocation.popover('hide');
		}
	</script>
	<?php
	//рисуем модальное окно только если виджет не обновляется, а рисуется новый
	if (!$this->bUpdate):
	?>
</div>

<?php
$this->beginWidget(
	'bootstrap.widgets.TbModal',
	array('id' => 'locationModal')
); ?>

	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4>Выбор города</h4>
	</div>

	<div class="modal-body" style="height: 100px;">
		Начните вводить название города, а затем выберите свой город из списка:
		<?= $sModalBody ?>
	</div>
<?php $this->endWidget(); ?>

<?php endif; ?>
