{{ Form::model($event, ['action' => 'event.update', 'data-id' => $event->id]) }}

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

{{ Form::hidden('id', $event->id) }}

<a class="close-reveal-modal">&#215;</a>

{{-- alert for errors --}}
<div data-alert class="alert-box alert" style="display: none;">
  <a href="#" class="close">&times;</a>
</div>

{{ Form::submit('Обновить', ['id' => 'button-event-update', 'class' => 'button small right']) }}
