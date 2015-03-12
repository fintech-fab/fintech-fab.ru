<div class="container-fluid breadcrumb-container">
	<div class="row">
<!--		<div class="col-md-12">-->
<!--			<ol class="breadcrumb">-->
<!--				<li><a href="index.html">Home</a></li>-->
<!--				<li class="active">Portfolio</li>-->
<!--			</ol>-->
<!--		</div>-->
		<div class="col-md-12">
			<h1>Наши проекты</h1>
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-8">
			<ul class="btn-group">
				<li class="btn btn-default" id="ToggleLayout"><span class="fa fa-bars"></span></li>
			</ul>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div id="Container" class="grid col-2">

				<a href="<?= URL::route('mobile2care') ?>" class="mix cat1">
					<div class="image">
						<img src="/assets/projects/M2C_700x450.png" alt="">
					</div>
					<div class="caption">
						<h4>Mobile2Care</h4>
						<p>Комплексная медицинская услуга для дистанционного мониторинга здоровья. Данное решение включает в себя устройство, выполненное в виде браслета с набором датчиков и сенсоров, и облачное решение для хранения и обработки полученных данных.</p>
					</div>
				</a>

				<a href="https://kreddy.ru" class="mix cat1">
					<div class="image">
						<img src="/assets/projects/KREDDY_700x450.png" alt="">
					</div>
					<div class="caption">
						<h4>КРЕДДИ</h4>
						<p>Сервис онлайн кредитования для мгновенного получения денег на счет мобильного телефона или банковской карты в режиме 24 х 7.</p>
					</div>
				</a>

				<a class="mix cat1">
					<div class="image">
						<img src="/assets/projects/MONEMOBO_700x450.png" alt="">
					</div>
					<div class="caption">
						<h4>MONEMOBO</h4>
						<p>Трансграничные денежные переводы с использованием мобильных технологий. Сервис позволяет переводить деньги с одного мобильного счета на другой без использования банковской карты или счета клиента.</p>
					</div>
				</a>

				<a href="<?= URL::route('wlp') ?>" class="mix cat1">
					<div class="image">
						<img src="/assets/projects/WLP_Online_700x450.png" alt="">
					</div>
					<div class="caption">
						<h4>WLP Online™</h4>
						<p>Современное решение для развития бизнеса МФО.</p>
						<p>Платформа по 100% онлайн кредитованию клиентов. Позволяет принимать, обрабатывать, учитывать заявки клиентов в режиме онлайн. Проводить дистанционную верификацию и идентификацию, а также переводить и принимать деньги по безналичным каналам платежей.</p>
					</div>
				</a>

				<a class="mix cat1">
					<div class="image">
						<img src="/assets/projects/Arbiter_700x450.png" alt="">
					</div>
					<div class="caption">
						<h4>Arbiter™</h4>
						<p>Инновационное решение для оценки кредитоспособности заемщика. Метод многофакторного анализа данных кредитных бюро, профилей социальных сетей, поведения в сети Интернет, активности на сайте позволяет получать качественный прогноз вероятности наступления дефолта клиента. Платформа имеет встроенную технологию дистанционной идентификации клиента и модуль верификации предоставляемых персональных данных.</p>
					</div>
				</a>


				<a href="<?= URL::route('anyany') ?>" class="mix cat1">
					<div class="image">
						<img src="/assets/projects/a2a.png" alt="Any2Any Platform">
					</div>
					<div class="caption">
						<h4>ANY2ANY</h4>
						<p>
							Сервис P2P денежных переводов.<br>
							Основан на интерактивной многоканальной платформе, поддерживающей как внутренние, так и трансграничные денежные транзакции.
						</p>
					</div>
				</a>

				<div class="clear clearfix"></div>
			</div>
			<div class="clear clearfix"></div>
		</div>
		<div class="clear clearfix"></div>
	</div>
	<div class="clear clearfix"></div>
</div>
<div class="clear clearfix"></div>
<script>
	$('#Container').mixItUp({
		animation: {
			duration: 190,
			effects: 'fade translateZ(-360px) stagger(34ms)',
			easing: 'ease'
		}
	});

	var layout = 'grid';

	$('#ToggleLayout').click(function(e){

		$(this).find('.fa').toggleClass('fa-bars').toggleClass('fa-th');
		$('#Container').toggleClass('grid');

		if(layout === 'grid') {
			layout = 'list';

		}

		else {
			layout = 'grid';
		}

		$('#Container').mixItUp('changeLayout', {
			containerClass: layout
		});

		e.preventDefault();

	});
</script>