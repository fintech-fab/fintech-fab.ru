<?php /* @var $this Controller */ ?>
<nav class="navbar navbar-top">
	<div class="navbar-inner navbar-inner-main">
		<div class="container">
			<div class="row">
				<!-- Special image
				<div class="new-year-left" style="margin: 5px 0px -145px -120px; float: left; background: url('<= Yii::app()->request->baseUrl; ?>/static/img/lenta9may.png') no-repeat; height: 140px; width: 112px"></div>
				-->

				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"></a>
				<a href="<?= Yii::app()->request->baseUrl; ?>/" class="brand">
					<img src="<?= Yii::app()->request->baseUrl; ?>/static/img/logo-slogan.png" alt="Kreddy" /> </a>

				<div class="span4">
					<?php
					$this->widget('UserCityWidget');
					?>
				</div>

			<span class="hotline pull-right span5">
				<small>
					Горячая линия
				</small>
				8 800 555-75-78
				<small>
					(бесплатно по России)
				</small>
			</span>
			</div>
		</div>
	</div>
</nav>