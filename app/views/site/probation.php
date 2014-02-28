<?php
/**
 * @var string $userMessage
 */
?>
<div class="row text-center"><a href="/"><b>Главная</b></a></div>
<div class="row">

	<div class="col-md-6 col-md-offset-3">
		<div class="row text-center"><h2>Раздел для стажировки</h2></div>
		<div class="row text-center"><?= $userMessage ?></div>
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" data-toggle="tab">Для кого</a></li>
			<li><a href="#tab2" data-toggle="tab">Что внутри</a></li>
			<li><a href="#tab3" data-toggle="tab">В чем польза</a></li>
			<li><a href="#tab4" data-toggle="tab">Я готов</a></li>
		</ul>

		<div class="tab-content probation">
			<div class="tab-pane active" id="tab1">
				<div class="row">
					Lorem ipsum dolor sit amet. Quae ab illo inventore veritatis. Ducimus, qui dolorem eum fugiat, quo
					voluptas sit, aspernatur aut perferendis. Odio dignissimos ducimus, qui dolorem ipsum, quia non
					numquam eius modi tempora. Porro quisquam est, omnis iste natus error sit voluptatem accusantium
					doloremque. Deserunt mollitia animi, id est eligendi. Facilis est eligendi optio, cumque nihil
					molestiae consequatur, vel illum, qui. Dolor sit, amet, consectetur, adipisci velit, sed quia.


				</div>
			</div>
			<div class="tab-pane" id="tab2">
				<div class="row">
					Банковском счету или мало чего страдала их карьера собственного. Если вы должны наладить связи.
					Держать своё слово, работать честно благородно. Репутация может надолго закрепиться за вами в китае.
					Многие бизнесмены ошибочно полагают, что вы усвоите урок и довольны положением дел. Других людей к
					чему не показатель достижений вашей компании боком встречал. Прослыл победителем, но в жизни в этой
					жизни, смогли ли что-то хорошее.
				</div>
			</div>
			<div class="tab-pane" id="tab3">
				<div class="row">
					Tут боец вспомнил, что в лесу. Нес красное знамя, по поводу чего все вымерли других домашних
					животных холодные. У нее темный лес чернеется аккуратно бреющийся. Двух яиц, сбивая с четырьмя
					ногами по полю слегка. Сочинения: живописца поразила поза её кружевного фартука могли бы так. Пьер
					безухов носил панталоны. Натурой, очень любила природу. Oбразoм приставала к автобусу бежала
					одевающаяся по которому.
				</div>
			</div>
			<div class="tab-pane" id="tab4">
				<?=
				Form::open(array(
					'class'  => 'form-horizontal',
					'role'   => 'form',
					'method' => 'post',
				)); ?>

				<div class="form-group">
					<label for="inputName" class="col-sm-2 control-label">Как Вас звать:</label>

					<div class="col-sm-10">
						<?=
						Form::input('text', 'username', '', array(
							'placeholder' => 'Фамилия Имя',
							'class'       => 'form-control',
							'id'          => 'inputName',
							'required'    => 'required',
						));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="inputAbout" class="col-sm-2 control-label">Немного о себе:</label>

					<div class="col-sm-10">
						<?=
						Form::textarea('about', '', array(
							'placeholder' => 'Пару слов',
							'class'       => 'form-control',
							'id'          => 'inputAbout',
							'required'    => 'required',
						));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail" class="col-sm-2 control-label">Email:</label>

					<div class="col-sm-10">
						<?=
						Form::input('email', 'email', '', array(
							'placeholder' => 'test@test.com',
							'class'       => 'form-control',
							'id'          => 'inputEmail',
							'required'    => 'required',
						));
						?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-default">Заявиться!</button>
					</div>
				</div>
				<?= Form::close(); ?>

				<div class="row">


				</div>
			</div>
		</div>
	</div>
</div>


