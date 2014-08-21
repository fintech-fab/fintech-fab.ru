{{ Form::open(['action' => 'event.create']) }}

{{ Form::label('event_sid', 'Строковый идентификатор(sid)') }}
{{ Form::input('text', 'event_sid') }}
{{ $errors->first('event_sid') }}

{{ Form::label('name', 'Имя') }}
{{ Form::input('text', 'name') }}

<a class="close-reveal-modal">&#215;</a>

{{ Form::submit('Добавить', ['id' => 'button-event-create', 'class' => 'button small right']) }}