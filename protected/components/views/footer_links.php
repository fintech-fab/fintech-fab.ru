<div id="footerlinks">

	<p>
		<?php

		foreach ($this->links as $l) {
			//заменяем пробелы на &nbsp; чтобы переносились целые ссылки
			$l->link_title = str_replace(' ','&nbsp;',$l->link_title);
			if ($l->link_url == '') { // поле "ссылка" пусто - значит, модальное окно
				echo CHtml::link($l->link_title, '#fl-' . $l->link_name, array('data-target' => '#fl-' . $l->link_name, 'data-toggle' => 'modal'));
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

	foreach ($this->links as $l) {
		if ($l->link_url == '') {
			$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'fl-' . $l->link_name));
			echo '	<div class="modal-header">';
			echo '		<a class="close" data-dismiss="modal">&times;</a>';
			echo '		<h2	>' . $l->link_title . '</h2>';
			echo '	</div>';
			echo '	<div class="modal-body">';
			echo '		' . $l->link_content;
			echo '	</div>';

			echo '	<div class="modal-footer">';

			$this->widget('bootstrap.widgets.TbButton', array(
				'label'       => 'Закрыть',
				'url'         => '#',
				'htmlOptions' => array('data-dismiss' => 'modal'),
			));

			echo '</div>';
			$this->endWidget();
		}
	}

	Yii::app()->clientScript->registerScript('openModalByUrl', "
            var sHash = $(location).attr('hash');
			if(typeof sHash!='undefined' && sHash.length){
                $(sHash).modal('show');
			}
", CClientScript::POS_READY);

	?>

</div>
