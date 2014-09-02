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
		var userLocation = jQuery('#userLocation');
		userLocation.popover({
			'selector': '',
			'placement': 'bottom',
			'content': '<?= $sDataContent; ?>',
			'html': 'true',
			'template':'<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>'
		});

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
					jQuery("#userCityWidget").html(html);
				}
			});
			userLocation.popover('hide');
		}

		<?php if(!$this->bCitySelected): ?>
		userLocation.popover('show');
		<?php endif ?>
	</script>
	<?php
	//рисуем модальное окно только если виджет не обновляется, а рисуется новый
	if (!$this->bUpdate):
	?>
</div>


<?php endif; ?>


<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Выбор города</h4>
			</div>
			<div class="modal-body">
				<div class="modal-body" style="height: 100px;">
					Начни вводить название города, а затем выбери свой город из списка:
					<?= $sModalBody ?>
				</div>
			</div>
			<div class="modal-footer">

			</div>
		</div>
	</div>
</div>