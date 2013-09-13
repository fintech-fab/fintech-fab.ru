<?php /* @var $this Controller */ ?>

<div class="page-divider"></div>

<div class="container">
	<div class="row">
		<div class="span12 footer">
			<div class="footer">
				<?php
				$this->widget('FooterLinksWidget', array(
					'links' => FooterLinks::model()->findAll(array('order' => 'link_order')),
				));
				?>
				<p>&copy; <?= SiteParams::copyrightYear() ?> ООО "Финансовые Решения"</p>
			</div>
		</div>
	</div>
</div>