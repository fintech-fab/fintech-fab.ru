<?php
/**
 * @var string $feedback
 */
?>
<div class="row">
	<div class="col-md-3">
		<img src="/assets/main/logo.png" height="100px" class="img" />
	</div>
	<div class="col-md-9">
		<h2 class="text-center">Эта стажировка проводится стажировщиками для стажирующихся стажеров</h2>
	</div>
</div>
<div class="col-md-offset-1 col-md-10">
	<ul class="nav nav-tabs">
		<li class="active">
			<a href="#tab1" data-toggle="tab">Для кого</a>
		</li>
		<li><a href="#tab2" data-toggle="tab">Что внутри</a></li>
		<li><a href="#tab3" data-toggle="tab">В чём польза</a></li>
		<li><a href="#tab4" data-toggle="tab">Я готов</a></li>
	</ul>


	<div class="tab-content">
		<div class="tab-pane active" id="tab1">


			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A culpa cupiditate dolorum enim eum impedit
				nemo repellendus saepe ut voluptatum. Alias at dolores error, excepturi fugit laboriosam possimus
				ratione recusandae. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cupiditate eos
				impedit repudiandae tempore! Deserunt dignissimos dolore doloremque dolores dolorum ipsum modi natus
				nesciunt, nulla, odio officia perferendis quaerat quisquam!</p>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A culpa cupiditate dolorum enim eum impedit
				nemo repellendus saepe ut voluptatum. Alias at dolores error, excepturi fugit laboriosam possimus
				ratione recusandae. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cupiditate eos
				impedit repudiandae tempore! Deserunt dignissimos dolore doloremque dolores dolorum ipsum modi natus
				nesciunt, nulla, odio officia perferendis quaerat quisquam!</p>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A culpa cupiditate dolorum enim eum impedit
				nemo repellendus saepe ut voluptatum. Alias at dolores error, excepturi fugit laboriosam possimus
				ratione recusandae. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cupiditate eos
				impedit repudiandae tempore! Deserunt dignissimos dolore doloremque dolores dolorum ipsum modi natus
				nesciunt, nulla, odio officia perferendis quaerat quisquam!</p>
		</div>
		<div class="tab-pane" id="tab2">
			<h4>Внутри ничего интересного - сплошной кодинг</h4>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A culpa cupiditate dolorum enim eum impedit
				nemo repellendus saepe ut voluptatum. Alias at dolores error, excepturi fugit laboriosam possimus
				ratione recusandae. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cupiditate eos
				impedit repudiandae tempore! Deserunt dignissimos dolore doloremque dolores dolorum ipsum modi natus
				nesciunt, nulla, odio officia perferendis quaerat quisquam!</p>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A culpa cupiditate dolorum enim eum impedit
				nemo repellendus saepe ut voluptatum. Alias at dolores error, excepturi fugit laboriosam possimus
				ratione recusandae. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cupiditate eos
				impedit repudiandae tempore! Deserunt dignissimos dolore doloremque dolores dolorum ipsum modi natus
				nesciunt, nulla, odio officia perferendis quaerat quisquam!</p>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A culpa cupiditate dolorum enim eum impedit
				nemo repellendus saepe ut voluptatum. Alias at dolores error, excepturi fugit laboriosam possimus
				ratione recusandae. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cupiditate eos
				impedit repudiandae tempore! Deserunt dignissimos dolore doloremque dolores dolorum ipsum modi natus
				nesciunt, nulla, odio officia perferendis quaerat quisquam!</p>
		</div>
		<div class="tab-pane" id="tab3">
			<h4>А вот пользы дофига и большой вагончик</h4>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A culpa cupiditate dolorum enim eum impedit
				nemo repellendus saepe ut voluptatum. Alias at dolores error, excepturi fugit laboriosam possimus
				ratione recusandae. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cupiditate eos
				impedit repudiandae tempore! Deserunt dignissimos dolore doloremque dolores dolorum ipsum modi natus
				nesciunt, nulla, odio officia perferendis quaerat quisquam!</p>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A culpa cupiditate dolorum enim eum impedit
				nemo repellendus saepe ut voluptatum. Alias at dolores error, excepturi fugit laboriosam possimus
				ratione recusandae. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cupiditate eos
				impedit repudiandae tempore! Deserunt dignissimos dolore doloremque dolores dolorum ipsum modi natus
				nesciunt, nulla, odio officia perferendis quaerat quisquam!</p>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A culpa cupiditate dolorum enim eum impedit
				nemo repellendus saepe ut voluptatum. Alias at dolores error, excepturi fugit laboriosam possimus
				ratione recusandae. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores cupiditate eos
				impedit repudiandae tempore! Deserunt dignissimos dolore doloremque dolores dolorum ipsum modi natus
				nesciunt, nulla, odio officia perferendis quaerat quisquam!</p>
		</div>
		<div class="tab-pane" id="tab4">
			<?= Form::open(); ?>
			<div class="form-group">
				<label for="inputName" class="col-sm-2 control-label">Как звать</label>

				<div class="col-sm-8">
					<?php
					echo Form::input('text', 'name', '', array(
						'placeholder' => 'тут имя',
						'class'       => 'form-control',
						'id'          => 'inputName',
						'required'    => 'required',
					));
					?>
				</div>
				</div>
				<div class="form-group">
					<label for="inputAbout" class="col-sm-2 control-label">О себе</label>

					<div class="col-sm-8">
						<textarea class="form-control" name="about" id="inputAbout" placeholder="О себе" rows="10" required></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail" class="col-sm-2 control-label">Email</label>

					<div class="col-sm-8">
						<input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email" required>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-default">Sign in</button>
					</div>
				</div>
			<?= Form::close(); ?>
		</div>
	</div>
</div>