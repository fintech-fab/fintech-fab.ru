<?php /* @var $this Controller */ ?>

<div class="page-divider"></div>

<div class="container">
	<div class="row">
		<div class="span12 footer">
			<div class="footer">
				<?php
				$this->widget('FooterLinksWidget');
				?>
				<p>&copy; <?= SiteParams::copyrightYear() ?> ООО "Финансовые Решения"</p>
			</div>
		</div>
	</div>
</div>