<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php
$this->widget('TopPageWidget');
?>


<div class="container container_12">
	<div class="grid_12">
	<article class="container">
	<section class="row">
		<div class="span12">
			<div class="row">
				<fieldset>
					<legend class="pay_legend">Инструкция по оплате с помощью банковской карты</legend>
					<div class="span12 pay_faq">
						<p>1. На сайте <a href="/">kreddy.ru</a> в нижней части выберете блок «Оплатить» или перейдите по <a href="https://pay.kreddy.ru/" target="_blank">этой ссылке</a>.</p>
						<p>2. Способ оплаты – «Банковской картой».</p>
						<p><a href="https://pay.kreddy.ru/" target="_blank"><img src="/static/img/pay_cc/cc.png" border="0" width="200"></a></p>
						<p>3. На экране появится окно для ввода данных с двумя блоками на выбор:</p>
						<p><img src="/static/img/pay_cc/blocks.png" border="0" width="628"></p>
						<p>4. В верхнем окне введите номер телефона (без 8 и +7, пример 9161234567).</p>
						<p>5. Во втором окне введите сумму для оплаты. (Информируем Вас, что оплата подписки возможна только в полном объеме одним платежом. Погашение займа возможно частями).</p>
						<p>6. Нажмите кнопку «Далее» и перейдите на страницу банка.</p>
						<p><img src="/static/img/pay_cc/bank.png" border="0" width="320"></p>
						<p>7. В открывшихся окнах введите необходимые данные, например: номер карты, дата окончания действия карты, CVC код, и т.д. После проверки верности введенных данных, нажмите кнопку «Оплатить».</p>
						<p>8. В течении нескольких минут Вам на телефон придет смс, подтверждающее платеж.</p>
						<p>9. <a href="https://pay.kreddy.ru/" target="_blank">Начать оплату</a></p>
					</div>
				</fieldset>
			</div>
		</div>
	</section>
</article>
</div>
</div>