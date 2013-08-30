<?php
/**
 * @var $this DefaultController
 * @var $data
 * @var $secureData
 * @var $smsState
 * @var $passForm
 * @var $passFormRender
 */

$this->breadcrumbs = array(
	$this->module->id,
);
?>
<h3 class="pay_legend">Личный кабинет</h3><br />
<div class="clearfix"></div>
<div class="row">
	<div class="span8">
		<?php

		echo '<pre>';
		print_r($data);
		echo '</pre>';
		if ($secureData['code'] == 0) {
			echo '<pre>';
			print_r($secureData);
			echo '</pre>';
		} else {
			echo "<h5>Для доступа к закрытым данным требуется авторизоваться по одноразовому СМС-паролю </h5>";
		}

		//if ($secureData['code'] == 7&&isset($passFormRender)) {
		//форма запроса СМС-пароля
		echo $passFormRender;
		//}
		?>
	</div>
	<div class="span4">
		<div class="well" style="padding: 8px; 0;">
			<?php
			$this->menu[] = array(
				'label' => 'Состояние подписки', 'url' => array(
					Yii::app()->createUrl('account')
				)
			);
			$this->menu[] = array('label' => 'История займов', 'url' => array(Yii::app()->createUrl('account')));
			$this->menu[] = array('label' => 'Выход', 'url' => array(Yii::app()->createUrl('account/logout')));

			$this->beginWidget('bootstrap.widgets.TbMenu', array(
				'type'        => 'pills', // '', 'tabs', 'pills' (or 'list')
				'stacked'     => true, // whether this is a stacked menu
				'items'       => $this->menu,
				'htmlOptions' => array('style' => 'margin-bottom: 0;'),
			));
			?>

			<div style="padding-left: 20px;">
				<h4><?= @$data['last_name'] . ' ' . @$data['first_name'] . ' ' . @$data['third_name']; ?></h4>

				<p>
					<?php if (@$data['balance'] < 0) {
						echo '<strong>Задолженность:</strong> ' . ($data['balance'] * -1) . ' руб. <br/>';
						echo '<strong>Вернуть до:</strong> ' . $data['dt_to'] . '<br/>';
					} else {
						echo '<strong>Баланс:</strong> ' . ($data['balance']) . ' руб. <br/>';
					}
					?></p>
			</div>
			<?php $this->endWidget(); ?>

		</div>
		<!-- sidebar -->
	</div>
</div>