<?php /* @var $this Controller */ ?>
<nav class="navbar navbar-top">
	<div id="header_wrap">
		<div id="header" class="container">
			<div class="row">
				<div class="col-sm-8 col-xs-12">
					<div class="row header_leftblock">
						<div class="col-md-6 col-xs-12 headlb_left">
							<a href="<?= Yii::app()->createAbsoluteUrl('/form'); ?>" class="up_logo">
								<img src="/static/newmain/images/index_logo.png" /> </a>
						</div>
						<div class="col-md-4 col-xs-12 headlb_right">

							<?php
							$this->widget('UserCityWidget');
							?>
						</div>
						<div class="col-md-2 col-xs-12 headlb_right">
							<div class="login_block">
								<a href="<?= Yii::app()->createUrl('/account/'); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-user"></span> Войти</a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-xs-12 headr_rightblock">
					<div class="headrbl">
						<p class="up_phone">8(499)704-31-72</p>

						<p class="up_phonetext">По будням с 9:00 до 18:00 по мск</p>

						<div class="up_line"></div>
						<div class="up_socico">
							<a href="https://vk.com/kreddyru" class="up_si usi_vk">
								<img src="/static/newmain/images/11.png" /> </a>
							<a href="https://www.facebook.com/pages/%D0%9A%D1%80%D0%B5%D0%B4%D0%B4%D0%B8-%D1%81%D0%B5%D1%80%D0%B2%D0%B8%D1%81-%D0%B2-%D1%82%D0%B2%D0%BE%D0%B5%D0%BC-%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%82%D0%B5/560011590772925?fref=ts" class="up_si usi_f">
								<img src="/static/newmain/images/10.png" /> </a>
							<a href="http://www.odnoklassniki.ru/group/53026435498223" class="up_si usi_o">
								<img src="/static/newmain/images/9.png" /> </a>
							<!--a href="#" class="up_si usi_i"> <img src="/static/newmain/images/8.png" /> </a-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</nav>