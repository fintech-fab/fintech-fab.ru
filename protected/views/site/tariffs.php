<style>

	table#rates-heading {
		margin-bottom: 0;
	}

	table.rates tr.head td {
		padding: 0;
	}

	table.rates td {
		width: 25%;
		text-align: center;
		vertical-align: middle;
	}

	table#rates tr td:nth-child(n+2) {
		background-color: #f6f6f6;
		/*border-left: 1px solid #cccccc;*/
		border-right: 1px solid #cccccc;
		text-align: center;
	}

	table#rates td:nth-child(1) {
		text-align: right;
		border-right: 1px solid #cccccc;
	}

	div#rates-heading button {
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
		height: 64px;
		/*border-bottom: none;*/
	}

	table.rates tr.head td button:focus {
		outline: 0;
	}

	div#rates-heading button#first {
		border-top-right-radius: 0;
		/*border-right: none;*/
	}

	div#rates-heading button#second {
		border-radius: 0;
		/*border-left: none;
		border-right: none;*/
	}

	div#rates-heading button#third {
		border-top-left-radius: 0;
		/*border-left: none;*/
	}

	div#rates-heading button.selected {
		background-color: #ded !important;
		background-image: none;
	}

	table#rates td#rates-info {
		text-align: left;
		border-bottom: 1px solid #cccccc;
		/*background-color: #f6f6f6 !important; */
		background-color: #ded !important;
	}

	div.info-block {
		padding: 10px 20px;
	}

	div.info-summary h2 {
		text-align: center;
	}

	div.accordion-inner {
		padding-left: 30px;
	}

	table#rates td.selected {
		background-color: #ded !important;
	}

	div.popover {
		min-width: 310px;
	}

	.accordion-heading .accordion-toggle {
		color: rgb(33, 31, 80);
		text-decoration: dashed;
	}

	.table th, .table td {
		border-bottom: 1px solid #dddddd;
	}

	.table td.selected.ignoreSelected {
		border-bottom: none;
	!important
	}

</style>

<div class="container">
	<h1>Наши тарифы</h1>
	<hr>
	<table id="rates" class="table rates" style="margin-left: -118px;">
		<tbody>
		<tr>
			<td style="border-top: none;"></td>
			<td><strong>МИНИМУМ</strong></td>
			<td><strong>ВСЕ ВКЛЮЧЕНО<br>Предоплата</strong></td>
			<td><strong>ВСЕ ВКЛЮЧЕНО<br>Постоплата</strong></td>
		</tr>
		<tr>
			<td>Функционал сервиса</td>
			<td>Ограниченный</td>
			<td>Полный</td>
			<td>Полный</td>
		</tr>
		<tr>
			<td>Способ получения денег</td>
			<td>Мобильный телефон</td>
			<td>Банковская карта, Мобильный телефон</td>
			<td>Банковская карта, Мобильный телефон</td>
		</tr>
		<tr>
			<td>Оплата абонентки</td>
			<td>-</td>
			<td>Сразу</td>
			<td>В день первого возврата денег</td>
		</tr>
		<tr>
			<td>Оплата %</td>
			<td>100% предоплата всех процентов</td>
			<td>В день возврата денег</td>
			<td>В день возврата денег</td>
		</tr>
		<tr>
			<td>% годовых</td>
			<td>До 1181,9%</td>
			<td>До 146%</td>
			<td>До 273,75%</td>
		</tr>
		<tr>
			<td>Стоимость</td>
			<td>До 6800 р.</td>
			<td>До 990 р.
				<span data-toggle="popover" data-html="true" data-content="<p style='text-align: center;'>Абонентка (750 р.)</p><p style='text-align: center;'>+</p><p style='text-align: center;'>Проценты за использование денег (до 240 р.)</p>" data-trigger="hover" data-placement="top"><i class="fa fa-question-circle"></i></span>
			</td>
			<td>До 1200 р.
				<span data-toggle="popover" data-html="true" data-content="<p style='text-align: center;'>Абонентка (750 р.)</p><p style='text-align: center;'>+</p><p style='text-align: center;'>Проценты за использование денег (до 450 р.)</p>" data-trigger="hover" data-placement="top"><i class="fa fa-question-circle"></i></span>
			</td>
		</tr>
		<!--
		<tr>
			<td>Период подключения сервиса</td>
			<td>30 дней</td>
			<td>30 дней</td>
			<td>30 дней</td>
		</tr>
		<tr>
			<td>Доступные суммы</td>
			<td>2000, 3000, 4000,<br>5000, 6000, 7000 р.</td>
			<td>2000, 3000, 4000,<br>5000, 6000, 7000 р.</td>
			<td>2000, 3000, 4000,<br>5000, 6000, 7000 р.</td>
		</tr>
-->
		<tr>
			<td style="border-bottom: none;"></td>
			<td><a href="/form" class="btn btn-primary">Подключиться</a></td>
			<td><a href="/form" class="btn btn-primary">Подключиться</a></td>
			<td><a href="/form" class="btn btn-primary">Подключиться</a></td>
		</tr>
		<tr>
			<td style="border-bottom: none; border-top: none;"></td>
			<td class="ignoreSelected"><a href="#" class="btn btn-link infoBlockBtn" onclick="selectInfoBlock(0)">Подробнее</a>
			</td>
			<td class="ignoreSelected"><a href="#" class="btn btn-link infoBlockBtn" onclick="selectInfoBlock(1)">Подробнее</a>
			</td>
			<td class="ignoreSelected"><a href="#" class="btn btn-link infoBlockBtn" onclick="selectInfoBlock(2)">Подробнее</a>
			</td>
		</tr>
		<tr style="display: none;">
			<td style="border-top: none; border-bottom: none"></td>
			<td colspan="3" id="rates-info" style="border-top: none;">
				<div id="info-rate-minimum" class="info-block"></div>
			</td>
		</tr>
		</tbody>
	</table>
	<div class="info-summary" style="text-align: center; margin-bottom: 40px;">
		<!-- <h2>Тариф &laquo;МИНИМУМ&raquo;</h2> -->
		<h5>Бери деньги на 30 дней.</h5>
		<h5>Получай деньги на мобильный, когда они тебе нужны.</h5>
		<h5>Возвращай задолженность и бери деньги повторно.</h5>
	</div>
</div>

<script>
	$(function () {
		$('a.infoBlockBtn').click(function () {
			return false;
		});
	});

	function selectInfoBlock(index) {
		$('.tariffs-modal:eq(' + index + ')').modal('show');
	}

	$(function () {
		$('table#rates-heading button').click(function () {
			var index = $(this).parent().index() - 1;
			selectInfoBlock(index);
		});

		$('span[data-toggle=popover]').popover();
	});
</script>

<div class="page-divider"></div>

<div class="modal hide fade tariffs-modal">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h3>Тариф &laquo;МИНИМУМ&raquo;</h3>
</div>
<div class="modal-body">
	<div class="accordion" id="accordion-rate-minimum">
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-minimum" href="#c11">
					<span class="dashed">Сколько стоит сервис?</span> </a>
			</div>
			<div id="c11" class="accordion-body collapse">
				<div class="accordion-inner">
					<p><strong>Стоимость за пользование деньгами в месяц – от 1800 до 6800 р.</strong></p>

					<p>Стоимость напрямую зависит от одобренной суммы и включает в себя только проценты за пользования
						деньгами.</p>
				</div>
			</div>
		</div>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-minimum" href="#c12">
					<span class="dashed">Какие суммы доступны?</span> </a>
			</div>
			<div id="c12" class="accordion-body collapse">
				<div class="accordion-inner">
					<p>2000, 3000, 4000, 5000, 6000, 7000 р.</p>

					<p>Не всегда твои желания совпадают с нашими возможностями – в некоторых случаях может быть одобрена
						меньшая сумма.</p>
				</div>
			</div>
		</div>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-minimum" href="#c13">
					<div id="c16" class="accordion-body collapse">
						<div class="accordion-inner">
							<ul>
								<li>КРЕДДИ – онлайн (проверка баланса, получение и возврат денег, продление подключения,
									выбор или смена тарифа, история платежей, архив документов)
								</li>
								<li>Один канал получения денег - Мобильный телефон.</li>
								<li>2 способа оплаты с моментальным зачислением и без комиссии (банковской картой на
									сайте, через терминалы Элекснет, МКБ)
								</li>
								<li>Служба поддержки 24/7 (SMS и E-mail информирование, Контактный центр
									8-800-555-75-78)
								</li>
							</ul>
						</div>
					</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-minimum" href="#c17">
						<span class="dashed">Как подключить?</span> </a>
				</div>
				<div id="c17" class="accordion-body collapse">
					<div class="accordion-inner">
						<ol>
							<li>Заполни анкету (потребуются 2 документа)</li>
							<li>Пройди видео- или фотоидентификацию – по твоему выбору.</li>
							<li>Выбери тариф «МИНИМУМ».</li>
							<li>Получи одобренную сумму.</li>
							<li>Оплати проценты за пользование деньгами сразу – от 1800 до 6800 руб. в зависимости от
								одобренной суммы денег.
							</li>
							<li>Получай деньги на мобильный телефон в любое время в течение месяца.</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-minimum" href="#c18">
						<span class="dashed">Как продлить срок с текущим тарифом?</span> </a>
				</div>
				<div id="c18" class="accordion-body collapse">
					<div class="accordion-inner">
						<ol>
							<li>Выбери тариф &laquo;МИНИМУМ&raquo;.</li>
							<li>Получи одобрение на доступную сумму.</li>
							<li>Оплати проценты за пользование деньгами сразу – от 1800 до 6800 руб.</li>
							<li>Получай деньги на мобильный телефон в любое время в течение месяца.</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-minimum" href="#c19">
						<span class="dashed">Как получить деньги?</span> </a>
				</div>
				<div id="c19" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>Нужны деньги?</p>
						<ol>
							<li>Отправь запрос через личный кабинет на сайте kreddy.ru или SMS-запрос на 7570.
								Подробности <a href="#">здесь</a>.
							</li>
							<li>Подтверди условия через личный кабинет или отправь OK на 7570.</li>
							<li>Получи деньги на мобильный телефон.</li>
							<li>Верни деньги за пользование в любое время в течение 30 дней любым удобным способом.</li>
							<li>Вернул деньги? Бери снова :)</li>
							<li>получай деньги быстро и просто, когда они нужны!</li>
						</ol>
						<p>Подробные условия сервиса <a href="#">здесь</a>.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-minimum" href="#c1a">
						<span class="dashed">Какой размер ПСК?</span> </a>
				</div>
				<div id="c1a" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>От 146 779 926 691 840 % до 611 168 843 442 894 000 000 %</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer"></div>
</div>

<div class="modal hide fade tariffs-modal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Тариф &laquo;ВСЕ ВКЛЮЧЕНО&raquo; (предоплата)</h3>
	</div>
	<div class="modal-body">
		<div class="accordion" id="accordion-rate-prepaid">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-prepaid" href="#c21">
						<span class="dashed">Сколько стоит сервис?</span> </a>
				</div>
				<div id="c21" class="accordion-body collapse">
					<div class="accordion-inner">
						<p><strong>Полная стоимость сервиса в месяц – до 990 р.</strong></p>

						<p>В стоимость включены:</p>
						<ul>
							<li>Абонентка за сервис - 750 р.</li>
							<li>Проценты за пользование деньгами – 8 р. в день.</li>
						</ul>
						<p>При условии возврата денег на 30-й день с момента оплаты абонентки, проценты за пользование
							составят 240 р., независимо от размера перевода.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-prepaid" href="#c22">
						<span class="dashed">Какие суммы доступны?</span> </a>
				</div>
				<div id="c22" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>2000, 3000, 4000, 5000, 6000, 7000 р.</p>

						<p>Не всегда твои желания совпадают с нашими возможностями – в некоторых случаях может быть
							одобрена меньшая сумма.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-prepaid" href="#c23">
						<span class="dashed">На сколько дней можно брать деньги?</span> </a>
				</div>
				<div id="c23" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>Наше требование – возврат денег на 30-й день с момента получения денег. Если хочешь вернуть
							раньше – как тебе нравится.</p>

						<p>Получение денег возможно в течение 30 дней с момента оплаты абонентки.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-prepaid" href="#c24">
						<span class="dashed">Как оплачивать?</span> </a>
				</div>
				<div id="c24" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>Абонентку оплачиваешь сразу.</p>

						<p>Проценты - в день возврата денег.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-prepaid" href="#c25">
						<span class="dashed">Сколько раз можно брать деньги?</span> </a>
				</div>
				<div id="c25" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>Без ограничений в период действия сервиса.</p>

						<p>Основное правило – возвращай всю сумму (деньги + проценты) и бери деньги повторно.</p>

						<p>При очередном переводе выбирай, куда тебе перевести деньги – на банковскую карту или
							мобильный телефон.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-prepaid" href="#c26">
						<span class="dashed">Что входит в абонентскую плату?</span> </a>
				</div>
				<div id="c26" class="accordion-body collapse">
					<div class="accordion-inner">
						<p><strong>Полный доступ ко всем возможностям сервиса!</strong></p>
						<ul>
							<li>КРЕДДИ – онлайн (проверка баланса, получение денег, продление подключения, выбор канала
								получения, выбор или смена тарифа, история платежей)
							</li>
							<li>Разные каналы получения денег - Банковская карта, Мобильный телефон. С возможностью
								смены канала получения в период подключения.
							</li>
							<li>5 способов оплаты с моментальным зачислением и без комиссии (банковской картой на сайте,
								через терминалы и кошельки, с мобильного телефона)
							</li>
							<li>Служба поддержки 24/7 (SMS и E-mail информирование, Контактный центр 8-800-555-75-78,
								получение денег по SMS)
							</li>
							<li>Интересные новости и уникальные предложения о том, как получить новый опыт, знания и
								навыки. Специальные условия от наших партнеров. Возможность оплаты сегодня за счет
								КРЕДДИ.
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-prepaid" href="#c27">
						<span class="dashed">Как подключить?</span> </a>
				</div>
				<div id="c27" class="accordion-body collapse">
					<div class="accordion-inner">
						<ol>
							<li>Заполни анкету (потребуются 2 документа)</li>
							<li>Пройди видео- или фотоидентификацию – по твоему выбору.</li>
							<li>Выбери тариф &laquo;ВСЕ ВКЛЮЧЕНО&raquo; с опцией &laquo;Оплатить абонентку
								сразу&raquo;.
							</li>
							<li>Получи одобрение на подключение сервиса и на доступный размер перевода.</li>
							<li>Оплати абонентку сразу – 750 руб. (любым удобным способом, на выбор - 7 вариантов)</li>
							<li>Сервис подключен! Получай деньги на карту или мобильный телефон в любое время в течение
								месяца.
							</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-prepaid" href="#c28">
						<span class="dashed">Как продлить срок с текущим тарифом?</span> </a>
				</div>
				<div id="c28" class="accordion-body collapse">
					<div class="accordion-inner">
						<ol>
							<li>Выбери тариф &laquo;ВСЁ ВКЛЮЧЕНО&raquo; с опцией &laquo;Оплатить абонентку
								сразу&raquo;</li>
							<li>Получи одобрение на продление сервиса и на доступный размер перевода.</li>
							<li>Оплати подключение сервиса – 750 руб. (любым удобным способом, на выбор - 7 вариантов)
							</li>
							<li>Сервис подключен! Получай деньги на карту или мобильный телефон в любое время в течение
								месяца.
							</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-prepaid" href="#c29">
						<span class="dashed">Как получить деньги?</span> </a>
				</div>
				<div id="c29" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>Нужны деньги для реализации новых возможностей?</p>
						<ol>
							<li>Отправь запрос через личный кабинет на сайте kreddy.ru или SMS-запрос на 7570.
								Подробности <a href="#">здесь</a>.
							</li>
							<li>Подтверди условия через личный кабинет или отправь OK на 7570.</li>
							<li>Получи деньги на мобильный телефон или банковскую карту.</li>
							<li>Верни деньги и оплати проценты за пользование в любое время в течение 30 дней любым
								удобным способом.
							</li>
							<li>Вернул деньги? Сервис еще активен? Бери снова :)</li>
							<li>Продли подключение сервиса КРЕДДИ и получай деньги быстро и просто, когда они нужны!
							</li>
						</ol>
						<p>Подробные условия сервиса <a href="#">здесь</a>.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-minimum" href="#c2a">
						<span class="dashed">Какой размер ПСК?</span> </a>
				</div>
				<div id="c2a" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>От 539,32 % до 407 237,98 %</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer"></div>
</div>

<div class="modal hide fade tariffs-modal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Тариф &laquo;ВСЕ ВКЛЮЧЕНО&raquo; (постоплата)</h3>
	</div>
	<div class="modal-body">
		<div class="accordion" id="accordion-rate-postpaid">
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-postpaid" href="#c31">
						<span class="dashed">Сколько стоит сервис?</span> </a>
				</div>
				<div id="c31" class="accordion-body collapse">
					<div class="accordion-inner">
						<p><strong>Полная стоимость сервиса в месяц – до 1200 р.</strong></p>

						<p>В стоимость включены:</p>
						<ul>
							<li>Абонентка за сервис - 750 р.</li>
							<li>Проценты за пользование деньгами – 15 р. в день.</li>
						</ul>
						<p>При условии возврата денег на 30-й день с момента оплаты абонентки, проценты за пользование
							составят 240 р., независимо от размера перевода.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-postpaid" href="#c32">
						<span class="dashed">Какие суммы доступны?</span> </a>
				</div>
				<div id="c32" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>2000, 3000, 4000, 5000, 6000, 7000 р.</p>

						<p>Не всегда твои желания совпадают с нашими возможностями – в некоторых случаях может быть
							одобрена меньшая сумма.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-postpaid" href="#c33">
						<span class="dashed">На сколько дней можно брать деньги?</span> </a>
				</div>
				<div id="c33" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>Наше требование – возврат денег на 30-й день с момента получения денег. Если хочешь вернуть
							раньше – как тебе нравится.</p>

						<p>Получение денег возможно в течение 30 дней после подтверждения подключения сервиса (отправка
							смс либо подтверждения в личном кабинете).</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-postpaid" href="#c34">
						<span class="dashed">Как оплачивать?</span> </a>
				</div>
				<div id="c34" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>Абонентку оплачиваешь с первым возвратом денег.</p>

						<p>Проценты - в день возврата денег.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-postpaid" href="#c35">
						<span class="dashed">Сколько раз можно брать деньги?</span> </a>
				</div>
				<div id="c35" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>Без ограничений в период действия сервиса.</p>

						<p>Основное правило – возвращай всю сумму (абонентка + деньги + проценты) и бери деньги
							повторно.</p>

						<p>При очередном переводе выбирай, куда тебе перевести деньги – на банковскую карту или
							мобильный телефон.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-postpaid" href="#c36">
						<span class="dashed">Что входит в абонентскую плату?</span> </a>
				</div>
				<div id="c36" class="accordion-body collapse">
					<div class="accordion-inner">
						<p><strong>Полный доступ ко всем возможностям сервиса!</strong></p>
						<ul>
							<li>КРЕДДИ – онлайн (проверка баланса, получение денег, продление подключения, выбор канала
								получения, выбор или смена тарифа, история платежей)
							</li>
							<li>Разные каналы получения денег - Банковская карта, Мобильный телефон. С возможностью
								смены канала получения в период подключения.
							</li>
							<li>5 способов оплаты с моментальным зачислением и без комиссии (банковской картой на сайте,
								через терминалы и кошельки, с мобильного телефона)
							</li>
							<li>Служба поддержки 24/7 (SMS и E-mail информирование, Контактный центр 8-800-555-75-78,
								получение денег по SMS)
							</li>
							<li>Интересные новости и уникальные предложения о том, как получить новый опыт, знания и
								навыки. Специальные условия от наших партнеров. Возможность оплаты сегодня за счет
								КРЕДДИ.
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-postpaid" href="#c37">
						<span class="dashed">Как подключить?</span> </a>
				</div>
				<div id="c37" class="accordion-body collapse">
					<div class="accordion-inner">
						<ol>
							<li>Заполни анкету (потребуются 2 документа)</li>
							<li>Пройди видео- или фотоидентификацию – по твоему выбору.</li>
							<li>Выбери тариф &laquo;ВСЕ ВКЛЮЧЕНО&raquo; с опцией &laquo;Оплатить абонентку в течение 30
								дней&raquo;.
							</li>
							<li>Получи одобрение на подключение сервиса и на доступный размер перевода.</li>
							<li>Подтверди подключение сервиса в личном кабинете или через SMS на 7570.</li>
							<li>Сервис подключен! Получай деньги на карту или мобильный телефон в любое время в течение
								месяца.
							</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-postpaid" href="#c38">
						<span class="dashed">Как продлить срок с текущим тарифом?</span> </a>
				</div>
				<div id="c38" class="accordion-body collapse">
					<div class="accordion-inner">
						<ol>
							<li>Выбери тариф &laquo;ВСЁ ВКЛЮЧЕНО&raquo; с опцией &laquo;Оплатить абонентку в течение 30
								дней&raquo;</li>
							<li>Получи одобрение на продление сервиса и на доступный размер перевода.</li>
							<li>Подтверди подключение сервиса в личном кабинете или через SMS на 7570.</li>
							<li>Сервис подключен! Получай деньги на карту или мобильный телефон в любое время в течение
								месяца.
							</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-postpaid" href="#c39">
						<span class="dashed">Как получить деньги?</span> </a>
				</div>
				<div id="c39" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>Нужны деньги для реализации новых возможностей?</p>
						<ol>
							<li>Отправь запрос через личный кабинет на сайте kreddy.ru или SMS-запрос на 7570.
								Подробности <a href="#">здесь</a>.
							</li>
							<li>Подтверди условия через личный кабинет или отправь OK на 7570.</li>
							<li>Получи деньги на мобильный телефон или банковскую карту.</li>
							<li>Верни деньги и оплати абонентку и проценты за пользование деньгами в любое время в
								течение 30 дней любым удобным способом.
							</li>
							<li>Вернул деньги? Сервис еще активен? Бери снова :)</li>
							<li>Продли подключение сервиса КРЕДДИ и получай деньги быстро и просто, когда они нужны!
							</li>
						</ol>
						<p>Подробные условия сервиса <a href="#">здесь</a>.</p>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-rate-minimum" href="#c3a">
						<span class="dashed">Какой размер ПСК?</span> </a>
				</div>
				<div id="c3a" class="accordion-body collapse">
					<div class="accordion-inner">
						<p>От 585,56 % до 30 341,06 %</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer"></div>
</div>


<div id="modal-frame" class="modal hide fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe style="width: 100%;height: 450px; border: 0;"></iframe>
			</div>
			<div class="modal-footer">
				<a data-dismiss="modal" class="btn" id="yw5" href="https://kreddy.ru/pages/view/kreddylinedetails#">Закрыть</a>
			</div>
		</div>
	</div>
</div>
<!-- footer end-->
 