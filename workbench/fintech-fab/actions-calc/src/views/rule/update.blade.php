{{ Form::model($rule, ['action' => 'rule.update', 'data-id' => $rule->id]) }}

<div class="name-field">
	{{ Form::label('name', 'Имя') }}
	{{ Form::input('text', 'name') }}
	<small class="hide" id="name-error">Имя обязательно.</small>
</div>

	{{ Form::label('rule', 'Правило') }}
	{{ Form::input('text', 'rule') }}

	{{ Form::label('event_id', 'Событие') }}
	{{ Form::input('text', 'event_id') }}

	{{ Form::label('signal_id', 'Сигнал') }}
	{{ Form::input('text', 'signal_id') }}

{{ Form::hidden('id', $rule->id) }}

<a class="close-reveal-modal">&#215;</a>

{{-- alert for errors --}}
<div data-alert class="alert-box alert" style="display: none;">
  <a href="#" class="close">&times;</a>
</div>

{{ Form::submit('Обновить', ['id' => 'button-rule-update', 'class' => 'button small right']) }}
