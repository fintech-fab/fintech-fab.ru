{{ Form::open(['action' => 'event.create']) }}


<div class="event-field">
	{{ Form::label('event_sid', 'Строковый идентификатор(sid)') }}
	{{ Form::input('text', 'event_sid') }}
	<small class="hide" id="event_sid-error">Sid обязателен, в нижнем регистре(уникальный).</small>
</div>

<div class="name-field">
	{{ Form::label('name', 'Имя') }}
	{{ Form::input('text', 'name') }}
	<small class="hide" id="name-error">Имя обязательно.</small>
</div>

<a class="close-reveal-modal">&#215;</a>

{{-- alert for errors --}}
<div data-alert class="alert-box alert" style="display: none;">
  <a href="#" class="close">&times;</a>
</div>

{{ Form::submit('Добавить', ['id' => 'button-event-create', 'class' => 'button small right']) }}
