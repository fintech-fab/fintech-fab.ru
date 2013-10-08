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

			<div class="hotline span3">
				<div class="row">
					8 800 555-75-78
				</div>
				<div class="clearfix"></div>
				<div class="row">
					<small>
						(бесплатно по России)
					</small>
				</div>
			</div>
			<span class="account span2">
				<?php
				if (Yii::app()->user->isGuest) {
					$this->widget('bootstrap.widgets.TbButton', array(
							'label' => 'Личный кабинет',
							'url'   => Yii::app()->createAbsoluteUrl('/account/login'),
						)
					);
				} else {
					$this->widget('bootstrap.widgets.TbButton', array(
							'label' => 'Выход',
							'icon'  => 'icon-off',
							'url'   => Yii::app()->createAbsoluteUrl('/account/logout'),
						)
					);
				}
				?>
			</span>
			</div>
		</div>
	</div>
</nav>