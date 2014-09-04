<div class="row">
	<div class="large-6 large-centered columns">
	<h4>Регистрация клиента</h4>

{{ Form::open(['action' => 'auth.registration']) }}

{{ Form::hidden('id', $terminal_id) }}
@if ($errors->has('id'))
	<div class="alert-box alert" data-alert>
		<p>{{ $errors->first('id') }}</p>
	</div>
@endif

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


{{-- submit --}}
{{ Form::submit('Регистрация', ['class' => 'button right']) }}

{{ Form::close() }}

	</div>
</div>