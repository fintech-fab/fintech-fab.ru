<?php
/**
 * @var TopPageWidget $this
 * @var array         $aItems
 */
?>
<div class="container top_page">
	<div class="row">
		<div class="hero-unit">
			<?php

			$this->widget(
				'application.components.utils.IkTbCarousel',
				array(
					'slide'              => true,
					'displayPrevAndNext' => false,
					'options'            => array(
						'interval' => 5000
					),
					'items'              => $aItems
				)
			);


			//TODO сделать управление виджетом из админки: добавление картинок, ссылки и прочее
			?>

		</div>
	</div>
</div>
<div class="page-divider"></div>
