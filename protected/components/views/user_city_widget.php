<?php

/**
 * @var $this UserCityWidget
 * @var $sDataContent
 * @var $sModalBody
 */
?>
<div id="userCityWidget">
	<?php
	$this->widget(
		'bootstrap.widgets.TbButton',
		array(
			'id'          => 'userLocation',
			'label'       => $this->sCityName.'<i class="caret-white"></i>',
			'type'        => 'link',
			'encodeLabel'=>false,
			'htmlOptions' => array(
				'class' => 'location' //TODO сделать свой класс для отображения названия города
			)
		)
	);
	?>
	<script type="text/javascript">
		var userLocation = $('#userLocation');
		userLocation.popover({
			'selector': '',
			'placement': 'bottom',
			'content': '<?= $sDataContent ?>',
			'html': 'true'
		});
		userLocation.popover('show');
	</script>
	<?php
	$this->beginWidget(
		'bootstrap.widgets.TbModal',
		array('id' => 'myModal')
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
</div>
