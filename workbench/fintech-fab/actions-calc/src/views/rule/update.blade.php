{{ Form::model($rule, ['action' => 'rule.update', 'data-id' => $rule->id]) }}

{{ Form::hidden('id', $rule->id) }}

<div class="row">
	<div class="large-12 columns">
		<div class="name-field">
			{{ Form::label('name', 'Имя') }}
			{{ Form::input('text', 'name') }}
			<small class="hide" id="name-error">Имя обязательно.</small>
		</div>
	</div>
</div>

<div class="row">
	<div class="large-12 columns">
		<fieldset>
			<legend>Условия правила</legend>
				{{ Form::hidden('rule') }}
				<div id="event-rules-translate"></div>
		</fieldset>
        <div class="text-center">
            <a class="button tiny" id="event-rule-add-condition" href="#"><i class="fi-plus"></i>&nbsp;условие</a>
        </div>
	</div>
</div>

<hr/>

<div class="row">
	<div class="large-12 columns">
	{{ Form::label('event_id', 'Событие') }}
	{{ Form::input('text', 'event_id') }}
	</div>
</div>

<div class="row">
	<div class="large-12 columns">
	{{ Form::label('signal_id', 'Сигнал') }}
	{{ Form::input('text', 'signal_id') }}

	{{ Form::submit('Обновить', ['id' => 'button-rule-update', 'class' => 'button small right']) }}
	</div>
</div>

<a class="close-reveal-modal">&#215;</a>

{{-- alert for errors --}}
<div data-alert class="alert-box alert" style="display: none;">
  <a href="#" class="close">&times;</a>
</div>
