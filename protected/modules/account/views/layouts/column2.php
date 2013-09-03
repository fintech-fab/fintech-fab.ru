<?php /* @var $this Controller */ ?>
<?php $this->beginContent('/layouts/main'); ?>
<div class="container" id="main-content">
	<div class="row">
		<div class="span8">
			<h3 class="pay_legend">Личный кабинет</h3><br />
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="row">
		<div class="span8">
			<?php echo $content; ?>
		</div>
		<!-- content -->
		<div class="span4">
			<div class="well" style="padding: 8px; 0;">
				<?php

				$this->beginWidget('bootstrap.widgets.TbMenu', array(
					'type'        => 'pills', // '', 'tabs', 'pills' (or 'list')
					'stacked'     => true, // whether this is a stacked menu
					'items'       => $this->menu,
					'htmlOptions' => array('style' => 'margin-bottom: 0;'),
				));
				?>

				<div style="padding-left: 20px;">
					<h4><?= @$this->clientData['last_name'] . ' ' . @$this->clientData['first_name'] . ' ' . @$this->clientData['third_name']; ?></h4>

					<p>
						<?php if (@$this->clientData['active_loan']['balance'] < 0) {
							echo '<strong>Задолженность:</strong> ' . (@$this->clientData['active_loan']['balance'] * -1) . ' руб. <br/>';
							echo '<strong>Вернуть до:</strong> ' . @$this->clientData['active_loan']['expired_to'] . '<br/>';
						} else {
							echo '<strong>Баланс:</strong> ' . (@$this->clientData['active_loan']['balance']) . ' руб. <br/>';
						}
						?></p>
				</div>
				<?php $this->endWidget(); ?>

			</div>
			<!-- sidebar -->
		</div>
	</div>
</div>
<?php $this->endContent(); ?>
