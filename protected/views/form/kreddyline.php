<?php
/* @var FormController $this */
/* @var IkTbActiveForm $form */
/* @var ClientCreateFormAbstract $oClientCreateForm */
/* @var $sSubView */

?>

<article>
	<!--================connect-credit================-->
	<div class="connect-credit">
		<div class="container">
			<div class="row-fluid">
				<h2>Как подключить КРЕДДИтную линию?</h2>
				<ul>
					<li id="asdaaa" class="tooltip_notice">
						<img src="static/kreddyline/images/connect_icon1.png" alt="">

						<p><a onclick="return false;" href="#" class="dotted">Заполни анкету<br />(потребуется 2
								документа)</a></p>

						<p class="hidden">
							Паспорт гражданина РФ и второй документ из списка:<br /> - Водительское удостоверение<br /> - Заграничный паспорт<br /> - Военный билет<br /> - Свидетельство
							СНИЛС<br />
						</p>
					</li>
					<li class="tooltip_notice">
						<img src="static/kreddyline/images/connect_icon2.png" alt="">

						<p><a onclick="return false;" href="#" class="dotted">Пройди идентификацию<br /> любым из 3
								способов</a>
						</p>

						<p class="hidden">
							- Видеоидентификация<br /> - Фотоидентификация<br /> - Через Android-приложение<br />
						</p>
					</li>
					<li class="tooltip_notice">
						<img src="static/kreddyline/images/connect_icon3.png" alt="">

						<p><a onclick="return false;" href="#" class="dotted">Получи решение</a></p>

						<p class="hidden">Одобренный лимит может отличаться от запрашиваемого</p>
					</li>
					<li class="tooltip_notice">
						<img src="static/kreddyline/images/connect_icon4.png" alt="">

						<p><a onclick="return false;" href="#" class="dotted">Оплати подключение<br />сразу или
								потом</a></p>

						<p class="hidden">
							Только абонентская плата за подключение! <br>Плати сразу 900 руб. за месяц или потом - 1000
							руб. за месяц </p>
					</li>
					<li class="tooltip_notice">
						<img src="static/kreddyline/images/connect_icon5.png" alt="">

						<p><a onclick="return false;" href="#" class="dotted">Получи деньги на карту<br /> или на
								мобильный телефон</a></p>

						<p class="hidden">Выбери удобный для тебя способ получения</p>

					</li>
				</ul>
			</div>
		</div>
	</div>
	<!--================/connect-credit================-->

	<!--================select-credit================-->
	<div class="select-credit">
		<div class="container">
			<div class="row-fluid">
				<h3>Подключи свою КРЕДДИтную линию
					<small>(<a href="/pages/view/kreddylinedetails" target="_blank">узнать подробности</a>)</small>
				</h3>

				<!--tab-->

				<div class="tab" id="formBody">

					<?php $this->renderPartial($sSubView, array('oClientCreateForm' => $oClientCreateForm)) ?>
				</div>
				<!--/tab-->
			</div>
		</div>
	</div>
	<!--================/select-credit================-->		<!--================info-credit================-->
	<div class="info-credit">
		<div class="container">
			<div class="row-fluid">
				<h6 class="inner1"><img src="static/kreddyline/images/info-credit-icon1.png" alt="">Безопасность </h6>

				<div class="div-center">Данные и подключение строго защищены стандартом информационной безопасности
				</div>
				<p>
					<a target="_blank" href="https://kreddy.ru/static/docs/pcidss.pdf"><img src="static/kreddyline/images/card_img1.png" alt=""></a>
					<img src="static/kreddyline/images/card_img2.png" alt="">
					<img src="static/kreddyline/images/card_img3.png" alt="">
					<img src="static/kreddyline/images/card_img4.png" alt="">
				</p>

			</div>
		</div>
		<div class="container">
			<div class="row-fluid">
				<h6 class="inner2"><img src="static/kreddyline/images/info-credit-icon2.png" alt="">Способы оплаты </h6>
				<ul>
					<li>
						<a href="<?= Yii::app()->createUrl('pages/view/cc'); ?>">
							<img src="static/kreddyline/images/info_payment_icon1.png" width="45" height="52"><br />
							Банковская карта </a>
					</li>
					<li>
						<a href="<?= Yii::app()->createUrl('pages/view/paymobile'); ?>">
							<img src="static/kreddyline/images/info_payment_icon2.png" width="45" height="52"><br />
							Мобильный телефон </a>
					</li>
					<li>
						<a href="<?= Yii::app()->createUrl('pages/view/yandexmoney'); ?>">
							<img src="static/kreddyline/images/info_payment_icon3.png" width="45" height="52"><br />
							Яндекс-деньги </a>
					</li>
					<li>
						<a href="<?= Yii::app()->createUrl('pages/view/qiwi'); ?>">
							<img src="static/kreddyline/images/info_payment_icon6.png" width="45" height="52"><br />
							QIWI-кошелек </a>
					</li>
					<li>
						<a href="<?= Yii::app()->createUrl('pages/view/elecsnet'); ?>">
							<img src="static/kreddyline/images/info_payment_icon4.png" width="45" height="52"><br />
							Терминал Элекснет </a>
					</li>
					<li>
						<a href="<?= Yii::app()->createUrl('pages/view/mkb'); ?>">
							<img src="static/kreddyline/images/info_payment_icon5.png" width="45" height="52"><br />
							Терминал МКБ </a>
					</li>
				</ul>
			</div>
		</div>
		<div class="container">
			<div class="row-fluid">
				<h6 class="inner3"><img src="static/kreddyline/images/info-credit-icon3.png" alt="">SMS-инфо</h6>
			<span><a href="<?= Yii::app()->createUrl('pages/view/smsinfopost'); ?>">Отправь соответствующий запрос на
					короткий номер 7570</a></span>
			</div>
		</div>
		<div class="container">
			<div class="row-fluid">
				<h6 class="inner4"><img src="static/kreddyline/images/info-credit-icon4.png" alt="">Вопросы и ответы
				</h6>
				<a class="a-center" href="<?= Yii::app()->createUrl('site/faq'); ?>">Возникли вопросы? Посмотрите
					ответы!</a>
			</div>
		</div>
	</div>
	<!--================/info-credit================-->

	<!--================data-box================-->
	<div class="data-box">
		<div class="container">
			<p>Сервис Кредди предоставляет услугу, стоимость которой фиксирована. Процентная ставка не зависит от суммы
				микрозайма и срока использования суммы микрозайма, а устанавливается фиксированным платежом, который
				оплачивается либо перед началом пользования КРЕДДИтной линией, либо в течение срока пользования
				КРЕДДИтной линией.</p>

			<p>Единая стоимость подключения КРЕДДИтной линии составляет 900 руб/мес, в случае внесения Абонентской платы
				перед началом пользования КРЕДДИтной линией.<br /> Единая стоимость подключения любой КРЕДДИтной линии
				составляет 1000 руб/месс, в случае внесения Абонентской платы в течение срока пользования КРЕДДИтной
				линией.</p>

			<p>При нарушении Заемщиком сроков возврата Суммы микрозайма или внесения Абонентской платы Займодавец вправе
				потребовать:<br /> - уплаты Заемщиком пени (неустойки) за каждый день просрочки в размере 40/60/100/150
				рублей для КРЕДДИтных линий 2000/3000/5000/7500 соответственно;<br /> - уплаты единовременного штрафа,
				начисляемого на 7 (седьмой) день просрочки, в размере 800/1200/2000/3000 рублей для КРЕДДИтных линий
				2000/3000/5000/7500 соответственно;<br /> - обратиться в коллекторское агентство для взыскания
				задолженности с Заемщика в досудебном порядке;<br /> - обратиться в суд за защитой нарушенных прав и
				своих законных интересов.</p>


			<p>Заемщик обязан вернуть сумму микрозайма в день окончания срока действия КРЕДДИтной линии. Возможности
				отсрочить срок возврата невозможно. </p>
		</div>
	</div>

	<!--================/data-box================-->

</article>
<?php
$sPath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('ext.myExt.assets') . '/') . '/js/ajax_form.js';
Yii::app()->clientScript->registerScriptFile($sPath, CClientScript::POS_BEGIN);

$this->widget('YaMetrikaGoalsWidget');
?>

