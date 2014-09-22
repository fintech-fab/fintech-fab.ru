<?php
/**
 * $this Controller
 */
?>
<div id="logo"><a href=""></a></div>
<div class="form">
	<div class="arrow-top"></div>
	<p>Перечислим до <span>7000 р</span>* за 15 минут</p>

	<form action="<?= Yii::app()->createUrl('/form/landing') ?>" method="post" id="form">
		<div class="line lname">
			<label>Фамилия</label> <input type="text" name="lname" />
		</div>
		<div class="line name">
			<label>Имя</label> <input type="text" name="name" />
		</div>
		<div class="line sname">
			<label>Отчество</label> <input type="text" name="sname" />
		</div>
		<div class="line date">
			<label>Дата рождения</label> <select name="day">
				<option>Число</option>
				<option value='1'>1</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
				<option value='4'>4</option>
				<option value='5'>5</option>
				<option value='6'>6</option>
				<option value='7'>7</option>
				<option value='8'>8</option>
				<option value='9'>9</option>
				<option value='10'>10</option>
				<option value='11'>11</option>
				<option value='12'>12</option>
				<option value='13'>13</option>
				<option value='14'>14</option>
				<option value='15'>15</option>
				<option value='16'>16</option>
				<option value='17'>17</option>
				<option value='18'>18</option>
				<option value='19'>19</option>
				<option value='20'>20</option>
				<option value='21'>21</option>
				<option value='22'>22</option>
				<option value='23'>23</option>
				<option value='24'>24</option>
				<option value='25'>25</option>
				<option value='26'>26</option>
				<option value='27'>27</option>
				<option value='28'>28</option>
				<option value='29'>29</option>
				<option value='30'>30</option>
				<option value='31'>31</option>
			</select> <select name="month">
				<option>Месяц</option>
				<option value='1'>январь</option>
				<option value='2'>февраль</option>
				<option value='3'>март</option>
				<option value='4'>апрель</option>
				<option value='5'>май</option>
				<option value='6'>июнь</option>
				<option value='7'>июль</option>
				<option value='8'>август</option>
				<option value='9'>сентябрь</option>
				<option value='10'>октябрь</option>
				<option value='11'>ноябрь</option>
				<option value='12'>декабрь</option>
			</select> <select name="year" id="year">
				<option>Год</option>

			</select>
		</div>
		<div class="line sex">
			<label>Пол</label> <select name="sex">
				<option value="">&nbsp;</option>
				<option value="1">Жен</option>
				<option value="2">Муж</option>
			</select>
		</div>
		<div class="line">
			<label>E-mail</label> <input type="text" name="email" id="email" />
		</div>
		<div class="line phone">
			<label>Мобильный телефон</label> +7
			(<input type="text" name="phone[]" maxlength="3" />)<input type="text" name="phone[]" maxlength="3" />-<input type="text" name="phone[]" class="n2" maxlength="2" />-<input type="text" name="phone[]" class="n2" maxlength="2" />
		</div>
		<div class="line rule">
			<input type="radio" name="rule" class="styled" /><span class="text-rule">Я подтверждаю достоверность введенных данных и даю согласие на их обработку (<a href="#" onclick="return doOpenModalFrame('/pages/viewPartial/usloviya', 'Условия обслуживания и передачи информации')">подробная
					информация</a>)</span>

			<div class="error-rule">Это поле обязательно для заполнения!</div>
		</div>
		<div class="line button">
			<div class="arrow-bottom"></div>
			<input type="submit" value="" class="btn" />
		</div>
		<div style="display:none">
			<input type="hidden" name="<?= Yii::app()->request->csrfTokenName ?>" value="<?= Yii::app()->request->csrfToken ?>">
		</div>
	</form>
</div>
<div class="woman"><p>Всего за <span>1 200р</span> в месяц</p></div>

<p id="options">От 78,21% годовых. Срок займа &mdash; 30 дней. ПСК &mdash; от 205,714% годовых. Оплата в конце срока
	использования займа.</p>

<div class="clear"></div>
<div class="bottom">
	<?php
	$this->widget('FooterLinksWidget');
	?>
</div>
<div class="f-cl"></div>
<?php
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'modal-frame'));
?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title"></h4>
		</div>
		<div class="modal-body">
			<iframe style="width: 100%;height: 450px; border: 0;"></iframe>
		</div>
		<div class="modal-footer">
			<?php
			$this->widget('bootstrap.widgets.TbButton', array(
				'label'       => 'Закрыть',
				'url'         => '#',
				'htmlOptions' => array('data-dismiss' => 'modal'),
			));
			?>
		</div>
	</div>
</div>
<?php $this->endWidget(); ?>
