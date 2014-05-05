<article>
<div class="hr-bg"></div>
<!--================connect-credit================-->
<div class="connect-credit">
	<div class="container">
		<div class="row-fluid">
			<h2>Как подключить КРЕДДИтную линию?</h2>
			<ul>
				<li>
					<img src="static/kreddyline/images/connect_icon1.png" alt="">

					<p>Заполни анкету<br />(потребуется 2 документа)</p>
				</li>
				<li>
					<img src="static/kreddyline/images/connect_icon2.png" alt="">

					<p>Пройди идентификацию<br /> любым из 3 способов</p>
				</li>
				<li>
					<img src="static/kreddyline/images/connect_icon3.png" alt="">

					<p>Получи решение</p>
				</li>
				<li>
					<img src="static/kreddyline/images/connect_icon4.png" alt="">

					<p>Оплати подключение<br />сразу или потом</p>
				</li>
				<li>
					<img src="static/kreddyline/images/connect_icon5.png" alt="">

					<p>Получи деньги на карту<br /> или на мобильный телефон</p>
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
			<h3>Выбери свою КРЕДДИтную линию</h3>

			<!--tab-->
			<?php
			$form = $this->beginWidget('application.components.utils.IkTbActiveForm', array(
				'id'                   => get_class($oClientCreateForm) . '_fast',
				'enableAjaxValidation' => true,
				'type'                 => 'inline',
				'clientOptions'        => array(
					'validateOnChange' => true,
					'validateOnSubmit' => false,
				),
				'action'               => Yii::app()->createUrl('/form/'),
			));
			?>

			<div class="tab">
				<ul class="bxslider">
					<li>
						<div class="del-bx-prev"></div>

						<!--КРЕДДИтный  лимит-->
						<div class="credit-limit">
							<b>Размер каждого перевода<br />равен одобренному лимиту</b>
							<ol>
								<li>
									<input type="radio" name="labeled" value="1" id="labeled_1" />
									<label for="labeled_1">2000</label>
								</li>
								<li>
									<input type="radio" name="labeled" value="1" id="labeled_2" />
									<label for="labeled_2">3000</label>
								</li>
								<li>
									<input type="radio" name="labeled" value="1" id="labeled_3" />
									<label for="labeled_3">5000</label>
								</li>
								<li>
									<input type="radio" name="labeled" value="1" id="labeled_4" />
									<label for="labeled_4">7500</label>
								</li>
							</ol>
						</div>
						<!--/КРЕДДИтный  лимит-->
					</li>
					<li>
						<!--Условия оплаты-->
						<div class="terms-of-payment">

							<ol>
								<li>
									<img src="static/kreddyline/images/tab_text_icon1.png" alt="">
									<input type="radio" name="labeled" value="1" id="labeled_5" />
									<label for="labeled_5">Оплатить
										сейчас<span>абонентская плата - 900 руб/мес</span></label>
								</li>
								<li>
									<img src="static/kreddyline/images/tab_text_icon2.png" alt="">
									<input type="radio" name="labeled" value="1" id="labeled_6" />
									<label for="labeled_6">Оплатить
										потом<span>абонентская плата - 1000 руб/мес</span></label>
								</li>
							</ol>
						</div>
						<!--/Условия оплаты-->
					</li>
					<li>
						<!--Куда перечислить деньги-->
						<div class="transfer-money">

							<ol>
								<li>
									<input type="radio" name="labeled" value="1" id="labeled_7" />
									<label for="labeled_7">Банковская
										карта<span><em>MasterCard</em>     VISA</span></label>
								</li>
								<li>
									<input type="radio" name="labeled" value="1" id="labeled_8" />
									<label for="labeled_8">Мобильный телефон<span><em>МТС</em>  <em>Билайн</em>   <em>Мегафон</em>  <em>ТЕЛЕ2</em></span></label>
								</li>
							</ol>
						</div>
						<!--/Куда перечислить деньги-->
					</li>
					<li>

						<!--Подключить-->
						<div class="hook-up">

							<span>Быстрая регистрация</span>

							<div class="row-input">
								<input class="w1 blured" type="text" value="Фамилия">
								<input class="w2 blured" type="text" value="Имя">
								<input class="w3 blured" type="text" value="Отчество">
							</div>
							<div class="row-input">
								<input class="w4 blured" type="text" value="Мобильный телефон">
								<input class="w5 blured" type="text" value="E-mail">
							</div>
							<p>
								<input type="checkbox" name="labeled" value="1" id="labeled_1" />
								<label for="labeled_1"> Я подтверждаю достоверность введенных данных и<br />даю согласие
									на их обработку (подробная информация) </label>
							</p>
							<input type="submit" value="Подключить">
						</div>
						<!--/Подключить-->
						<div class="del-bx-next"></div>
					</li>
				</ul>

				<div id="bx-pager">
					<div class="del-tal-left-col"></div>
					<a class="del-left-but" data-slide-index="0" href=""><img class="act-corner act-corner-top" src="static/kreddyline/images/tab_corner_top.png"><img class="no-act" src="static/kreddyline/images/tab_icon1.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon1_act.png" alt=""><span><em>КРЕДДИтный<br />
								лимит</em></span></a>
					<a class="one-line" data-slide-index="1" href=""><img class="act-corner" src="static/kreddyline/images/tab_corner.png"><img class="no-act" src="static/kreddyline/images/tab_icon2.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon2_act.png" alt=""><span><em>Условия
								оплаты</em></span></a>
					<a data-slide-index="2" href=""><img class="act-corner" src="static/kreddyline/images/tab_corner.png"><img class="no-act" src="static/kreddyline/images/tab_icon3.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon3_act.png" alt=""><span><em>Куда
								перечислить<br /> деньги</em></span></a>
					<a class="one-line last" data-slide-index="3" href=""><img class="act-corner act-corner-bot" src="static/kreddyline/images/tab_corner_bot.png"><img class="no-act" src="static/kreddyline/images/tab_icon4.png" alt=""><img class="act" src="static/kreddyline/images/tab_icon4_act.png" alt=""><span><em>Подключить</em></span></a>
				</div>

			</div>
			<?php
			$this->endWidget();
			?>
			<!--/tab-->
		</div>
	</div>
</div>
<!--================/select-credit================-->
<div class="hr-bg"></div>
<!--================info-credit================-->
<div class="info-credit">
	<div class="container">
		<div class="row-fluid">
			<h6 class="inner1"><img src="static/kreddyline/images/info-credit-icon1.png" alt="">Безопасность</h6>

			<div class="div-center">Данные строго защищены стандартом информационной безопасности</div>
			<p>
				<a href="#"><img src="static/kreddyline/images/card_img1.png" alt=""></a>
				<a href="#"><img src="static/kreddyline/images/card_img2.png" alt=""></a>
				<a href="#"><img src="static/kreddyline/images/card_img3.png" alt=""></a>
				<a href="#"><img src="static/kreddyline/images/card_img4.png" alt=""></a>
			</p>

		</div>
	</div>
	<div class="container">
		<div class="row-fluid">
			<h6 class="inner2"><img src="static/kreddyline/images/info-credit-icon2.png" alt="">Способы оплаты</h6>
			<ul>
				<li><a href="<?= Yii::app()->createUrl('pages/view/cc'); ?>">Банковская карта</a></li>
				<li><a href="<?= Yii::app()->createUrl('pages/view/paymobile'); ?>">Мобильный телефон</a></li>
				<li><a href="<?= Yii::app()->createUrl('pages/view/yandexmoney'); ?>">Яндекс-деньги</a></li>
				<li><a href="<?= Yii::app()->createUrl('pages/view/qiwi'); ?>">QIWI-кошелек</a></li>
				<li><a href="<?= Yii::app()->createUrl('pages/view/elecsnet'); ?>">Терминал Элекснет</a></li>
				<li><a href="<?= Yii::app()->createUrl('pages/view/mkb'); ?>">Терминал МКБ</a></li>
			</ul>
		</div>
	</div>
	<div class="container">
		<div class="row-fluid">
			<h6 class="inner3"><img src="static/kreddyline/images/info-credit-icon3.png" alt="">SMS-инфо</h6>
			<span><a href="<?= Yii::app()->createUrl('pages/view/smsinfo'); ?>">Отправь соответствующий запрос на
					короткий номер 7570 (для КРЕДДИтной линии,<br /> для клиентов с Пакетом КРЕДДИ)</a></span>
		</div>
	</div>
	<div class="container">
		<div class="row-fluid">
			<h6 class="inner4"><img src="static/kreddyline/images/info-credit-icon4.png" alt="">Вопросы и ответы</h6>
			<a class="a-center" href="<?= Yii::app()->createUrl('site/faq'); ?>">Возникли вопросы? Посмотрите
				ответы!</a>
		</div>
	</div>
</div>
<!--================/info-credit================-->

<!--================data-box================-->
<div class="data-box">
	<div class="container">
		<p>
			Сервис Кредди предоставляет услугу, стоимость которой фиксирована. Процентная ставка не зависит от суммы
			микрозайма и срока использования суммы микрозайма, а устанавливается фиксированным платежом, который
			оплачивается либо перед началом пользования КРЕДДИтной линией, либо после окончания срока пользования
			КРЕДДИтной линией. <br /> При подключении КРЕДДИтной линии 2000р, стоимость подключения составляет 900 р
			/мес.<br /> При подключении КРЕДДИтной линии 7500р, стоимость подключения составляет 900 р /мес. </p>

		<p>
			При нарушении Заемщиком сроков возврата Суммы микрозайма Займодавец вправе потребовать:<br /> - уплаты
			Заемщиком пени (неустойки) за каждый день просрочки в размере 2% от суммы микрозайма;<br /> - уплаты
			единовременного штрафа, начисляемого на 7 (седьмой) день просрочки в размере 40% от суммы микрозайма;<br />
			- обратиться в коллекторское агентство для взыскания задолженности с Заемщика в досудебном порядке;<br /> -
			обратиться в суд за защитой нарушенных прав и своих законных интересов. </p>

		<p>
			Заемщик обязан вернуть сумму микрозайма в день окончания срока действия КРЕДДИтной линии. Возможности
			отсрочить срок возврата невозможно. </p>
	</div>
</div>
<!--================/data-box================-->

</article>
