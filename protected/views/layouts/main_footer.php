<?php /* @var $this Controller */ ?>

<div class="page_separator"></div>

<div id="main-footer">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center footer_hrefs">
				<?php
				$this->widget('FooterLinksWidget');
				?>
			</div>
			<div class="clearfix"></div>
			<div class="col-sm-8 col-sm-offset-2 col-xs-offset-12 col-xs-offset-0 text-center footer_text">
				<p>Микрофинансовая организация общество с ограниченной ответственностью “Финансовые Решения”
					Регистрационный номер записи в государственном реестре МФО №2110177000213 от 19 июля 2011 г. ОГРН
					1117746371270<br> г. Москва, Гончарная наб. 1, стр. 4<br> тел. 8-800-555-75-78</p>
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
