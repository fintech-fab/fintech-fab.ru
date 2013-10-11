<div id="footerlinks">
	<script type="text/javascript">
		function doOpenModalFrame(src, title) {
			var $modal = $("#modal-frame");
			$modal.modal({});
			$modal.find('iframe').attr('src', src);
			if (!title) {
				title = '';
			}
			$modal.find('.modal-title').html(title);
			$modal.modal('show');
			return false;
		}
	</script>
	<p>
		<?php
		//TODO подумать насчет переноса логики кода из представления в класс виджета
		foreach ($this->links as $l) {
			//заменяем пробелы на &nbsp; чтобы переносились целые ссылки
			$l->link_title = str_replace(' ', '&nbsp;', $l->link_title);
			if ($l->link_url == '') { // поле "ссылка" пусто - значит, модальное окно
				?>

				<a href="#" class="dotted" onclick="return doOpenModalFrame('<?= Yii::app()->createAbsoluteUrl("footerLinks/view/$l->link_name"); ?>', '<?= $l->link_title ?>');"><?= $l->link_title ?></a>

			<?php

			} elseif (strpos($l->link_url, "/") == 0 && strpos($l->link_url, ".") === false) { // в поле "ссылка" первый символ - "/" и в конце нет точки - значит, относительный url и не файл, открываем в этом же окне (не модальном)
				echo CHtml::link($l->link_title, $l->link_url);
			} else { // иначе открываем в новом окне
				echo CHtml::link($l->link_title, $l->link_url, array('target' => '_blank'));
			}

			if ($l != end($this->links)) {
				echo ' &middot; ';
			}
		}
		?>
	</p>

	<?php

	$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'modal-frame'));
	?>

	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<!--button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button-->
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


	<?php

	Yii::app()->clientScript->registerScript('openModalByUrl', "
            var sHash = $(location).attr('hash');
			if(typeof sHash!='undefined' && sHash.length){
                $(sHash).modal('show');
			}
", CClientScript::POS_READY);

	?>

</div>
