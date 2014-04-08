<?php /* @var $this Controller */ ?>

<div class="page-divider"></div>

<div class="container">
	<div class="row">
		<div class="span12 footer">
			<div class="footer">
				<?php
				$this->widget('FooterLinksWidget');
				?>
				<p>&copy; <?= SiteParams::copyrightYear() ?> ООО "Финансовые Решения", 115172, г. Москва, Гончарная
					наб., 1, стр.4, ОГРН 1117746371270</p>
			</div>
		</div>
	</div>
</div>


<?php
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'modal-frame'));
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title"></h4>
		</div>
		<div class="modal-body">
			<iframe style="width: 100%;height: 450px; border: 0;"></iframe>
		</div>
		<div class="modal-footer">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'label'       => 'Закрыть',
				'url'         => '#',
				'htmlOptions' => array('data-dismiss' => 'modal'),
			));
			?>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
