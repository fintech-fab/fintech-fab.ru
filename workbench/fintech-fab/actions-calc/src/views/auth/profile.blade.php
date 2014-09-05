<div class="row">
	<div class="large-12 large-centered columns">
	<h4>Редактирование данных клиента</h4>

{{ Form::model($terminal, ['action' => 'auth.profile', 'id' => 'form-auth-profile']) }}

{{ Form::hidden('id') }}

{{-- name --}}
@if ($errors->has('name'))
	{{ Form::label('name', 'Имя', ['class' => 'error']) }}
	{{ Form::input('text', 'name', null, ['class' => 'error']) }}
	{{ $errors->first('name', '<small class="error">:message</small>') }}
@else
	{{ Form::label('name', 'Имя') }}
	{{ Form::input('text', 'name') }}
@endif


{{-- url --}}
@if ($errors->has('url'))
	{{ Form::label('url', 'URL',[
        'data-tooltip' => '',
        'class' => 'has-tip tip-top error',
        'aria-haspopup' => 'true',
        'title' => 'URL для отправки из очереди.',
        ]) }}

    {{ Form::input('text', 'url', null, ['placeholder' => 'http://example.net', 'class' => 'error']) }}
	{{ $errors->first('url', '<small class="error">:message</small>') }}
@else

	{{ Form::label('url', 'URL',[
        'data-tooltip' => '',
        'class' => 'has-tip tip-top',
        'aria-haspopup' => 'true',
        'title' => 'URL для отправки из очереди.'
        ]) }}

    {{ Form::input('text', 'url', null, ['placeholder' => 'http://example.net']) }}
@endif


{{-- foreign queue --}}
@if ($errors->has('foreign_queue'))
	{{ Form::label('foreign_queue', 'Имя внешней очереди', ['class' => 'errors']) }}
	{{ Form::input('text', 'foreign_queue', null, ['class' => 'error']) }}
	{{ $errors->first('foreign_queue', '<small class="error">:message</small>') }}
@else
	{{ Form::label('foreign_queue', 'Имя внешней очереди') }}
	{{ Form::input('text', 'foreign_queue') }}
@endif


{{-- foreign job --}}
@if ($errors->has('foreign_job'))
	{{ Form::label('foreign_job', 'Класс обработки внешней очереди', [
	                 'data-tooltip' => '',
	                 'class' => 'has-tip tip-top error',
	                 'aria-haspopup' => 'true',
	                 'title' => 'Класс обработки очереди. Обработанные данные посланы в очередь,
	                                берутся и обрабатываются этим классом.'
	                 ]) }}
	{{ Form::input('text', 'foreign_job', null, ['placeholder' => 'Namespace\Sub\JobHandler', 'class' => 'error']) }}
	{{ $errors->first('foreign_job', '<small class="error">:message</small>') }}
@else
	{{ Form::label('foreign_job', 'Класс обработки внешней очереди', [
	                 'data-tooltip' => '',
	                 'class' => 'has-tip tip-top',
	                 'aria-haspopup' => 'true',
	                 'title' => 'Класс обработки очереди. Обработанные данные посланы в очередь,
	                                берутся и обрабатываются этим классом.'
	                 ]) }}
	{{ Form::input('text', 'foreign_job', null, ['placeholder' => 'Namespace\Sub\JobHandler']) }}
@endif

{{-- key --}}
{{ Form::label('key', 'Ключ', [
                  'data-tooltip' => '',
                  'class' => 'has-tip tip-top',
                  'aria-haspopup' => 'true',
                  'title' => 'Ключ(токен) для идентификации терминала клиента.
                                Будет создан случайный, если оставить поле пустым.'
                  ]) }}
{{ Form::input('text', 'key') }}

<div class="large-12 columns">
	{{ Form::label('profile-password-change-trigger', 'Сменить пароль?') }}
	{{ Form::checkbox('change_password', 1, null, ['id' => 'profile-password-change-trigger']) }}
</div>

{{-- keep password change container opened, if chage_password checked --}}
<div id="profile-password-change-container" @if(Input::old('change_password') < 1) style="display: none; @endif">

	{{-- current password --}}
	@if ($errors->has('current_password'))
		{{ Form::label('current_password', 'Текущий пароль', ['class' => 'error']) }}
		{{ Form::password('current_password', ['class' => 'error']) }}
		{{ $errors->first('current_password', '<small class="error">:message</small>') }}
	@else
		{{ Form::label('current_password', 'Текущий пароль') }}
		{{ Form::password('current_password') }}
	@endif

	{{-- password --}}
	@if ($errors->has('password'))
		{{ Form::label('password', 'Пароль', ['class' => 'error']) }}
		{{ Form::password('password', ['class' => 'error']) }}
		{{ $errors->first('password', '<small class="error">:message</small>') }}
	@else
		{{ Form::label('password', 'Пароль') }}
		{{ Form::password('password') }}
	@endif

	{{-- password confirm --}}
	@if ($errors->has('password_confirmation'))
		{{ Form::label('password_confirmation', 'Пароль ещё раз', ['class' => 'error']) }}
		{{ Form::password('password_confirmation', ['class' => 'error']) }}
		{{ $errors->first('password_confirmation', '<small class="error">:message</small>') }}
	@else
		{{ Form::label('password_confirmation', 'Пароль ещё раз') }}
		{{ Form::password('password_confirmation') }}
	@endif

</div>

@if(Session::has('auth.profile.success'))
	<div class="row">
		<div class="large-12 columns">
			<div id="auth-profile-updated" class="alert-box success text-center">
				{{ Session::pull('auth.profile.success') }}
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
		  $('#auth-profile-updated').fadeOut(7000);
		});
	</script>
@endif

{{-- submit --}}
{{ Form::button('Сохранить', ['class' => 'button right', 'id' => 'button-profile-update']) }}

<a class="close-reveal-modal">&#215;</a>

<script type="text/javascript">
	// reinit tooltips, for modal
	$(document).ready(function() {
		$(document).foundation('tooltip');
	});
</script>

	</div>
</div>