<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Payments - Elecsnet';
$this->breadcrumbs=array(
	'Elecsnet',
);
?>
<?php
$this->beginWidget('TopPageWidget');
$this->endWidget();
?>
<div class="container container_12">
	<div class="grid_12">

		<article class="container">
			<section class="row">

				<div class="span12">
					<div class="row">
						<fieldset>
							<legend class="pay_legend">Инструкция по оплате в сети терминалов Элекснет</legend>
							<div class="span12 pay_faq">
								<p>Услуги «Кредди» вы можете оплатить в российской сети терминалов оплаты Элекснет. Многие терминалы работают круглосуточно. <a href="http://elecsnet.ru/terminals/addresses/" target="_blank">Адреса терминалов</a>.</p>
								<p>Комиссия составляет 0%.</p>
								<p>Круглосуточная служба поддержки: 8 (495) 787-29-64.</p>
								<p class="pay_attention">Инструкция по оплате:</p>
								<p class="pay_attention">Внимание! Максимальная сумма оплаты — 14 999 руб.</p>
								<ol>
									<li>Зайдите в меню <strong>"Переводы в банки"</strong> на главной странице интерфейса терминала.</li>
									<img src="./static/img/pay_faq_elecsnet_1.jpg" alt="1" width="80%">
									<li>Перейдите в раздел <strong>"Микрофинансовые организации"</strong>, найдите кнопку с логотипом «Кредди» и нажмите ее.</li>
									<img src="./static/img/pay_faq_elecsnet_2.jpg" alt="2" width="80%">
									<li>Введите 11-значный номер своего телефона, за которым закреплен Ваш займ.</li>
									<img src="./static/img/pay_faq_elecsnet_3.jpg" alt="3" width="80%">
									<li>Подтвердите правильность введения данных и нажмите кнопку <strong>"Вперед"</strong>.</li>
									<img src="./static/img/pay_faq_elecsnet_4.jpg" alt="4" width="80%">
									<li>Внесите сумму платежа, вставляя деньги в купюроприемник терминала. Максимальная сумма — 14 999 руб. Нажмите <strong>"Оплатить"</strong>.</li>
									<img src="./static/img/pay_faq_elecsnet_5.jpg" alt="5" width="80%">
									<li>Нажмите <strong>"Завершить операцию"</strong>. Возьмите квитанцию о зачислении денежных средств и сохраните ее. При отсутствии квитанции необходимо узнать номер терминала, через который был совершен платеж, на главной странице интерфейса в разделе <strong>"Информация"</strong>.</li>
									<img src="./static/img/pay_faq_elecsnet_6.jpg" alt="6" width="80%">
								</ol>
								<p>Уважаемые клиенты, воспользуйтесь сервисом <a href="http://www.elecsnet.ru/notebook/create/" target="_blank">"Записная Книжка"</a> для сохранения платежных реквизитов. Дополнительная информация на сайте <a href="http://www.elecsnet.ru" target="_blank">www.elecsnet.ru</a></p>
							</div>
						</fieldset>
					</div>
				</div>

			</section>
			<section class="row tpl_after">

				<div class="span12">
					<div class="row">
						<fieldset>
							<legend class="pay_legend">Данные для оплаты через Кошелек Элекснет</legend>

							<div class="span2 pay_faq">
								<img src="/static/img/pay_logo_elecsnet.gif" alt="logo" height="76" border="0" width="91">
							</div>

							<div class="span10 pay_faq">
								<p><a href="http://elecsnet.ru/notebook/" target="_blank">Что такое Кошелек Элекснет?</a></p>
								<p>Нет кошелька? <a href="https://services.elecsnet.ru/notebook/CreateWallet.aspx" target="_blank">Бесплатная регистрация за 1 минуту!</a></p>
								<p>Оплата производится на сайте Кошелька Элекснет.</p>
								<p>Для проведения оплаты введите номер заказа и нажмите на кнопку "Оплатить", после чего откроется страница c готовой формой оплаты. Введите номер Кошелька Элекснет, сумму для пополнения и платежный пароль.</p>
								<p>Оплата производится <strong>БЕЗ КОМИССИИ</strong>.</p>
								<strong>Зачисление</strong> средств  производится в режиме <strong>online</strong>.
							</div>

						</fieldset>
					</div>
				</div>

			</section>
			<section class="row tpl_after offset1">

				<form method="post" action="https://services.elecsnet.ru/Notebook/RemotePayment/Default.aspx" class="form-horizontal" id="form">
					<div class="span12">
						<div class="row">
							<fieldset>

								<div class="control-group toalert">
									<label class="control-label" for="reqID">Телефон</label>

									<div class="controls">
										<div class="input-prepend">
											<span class="add-on">+7</span><input class="required input-large" id="reqID" name="reqID" data-inputmask="9999999999" type="text">
										</div>
									</div>
								</div>

								<input name="merchantID" value="9787" type="hidden">
								<input name="Amount" value="0" type="hidden">
								<input name="MerchantBackUrl" value="http://kreddy.ru" type="hidden">

							</fieldset>
						</div>
					</div>
					<div class="offset2 span2">
						<a href="index.php?r=site/page&view=payments" class="btn btn-info btn-large nxt">← Назад</a>
					</div>
					<div class="offset0 span2">
						<div class="button_elecsnet_add_logo"></div>
						<button id="acqbutton" type="submit" class="btn btn-success btn-large">Оплатить</button>
					</div>
				</form>

			</section>
		</article>

	</div>
</div>