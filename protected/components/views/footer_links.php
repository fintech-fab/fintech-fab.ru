<div id="footerlinks">

	<p>
		<?php
		//TODO подумать насчет переноса логики кода из представления в класс виджета
		foreach ($this->links as $l) {
			//заменяем пробелы на &nbsp; чтобы переносились целые ссылки
			$l->link_title = str_replace(' ', '&nbsp;', $l->link_title);
			if ($l->link_url == '') { // поле "ссылка" пусто - значит, модальное окно
				?>

				<a href="#" class="dotted" onclick="return doOpenModalFrame('<?=
				Yii::app()
					->createAbsoluteUrl("footerLinks/view/$l->link_name"); ?>', '<?= $l->link_title ?>');"><?= $l->link_title ?></a>

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

</div>
