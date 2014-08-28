{{ Form::open(['action' => 'rule.create']) }}

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
				<div class="event-rules-translate"></div>
		</fieldset>
        <div class="text-center">
            <button class="button tiny event-rule-add-condition"><i class="fi-plus"></i>&nbsp;условие</button>
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

	{{ Form::submit('Создать', ['id' => 'button-rule-create', 'class' => 'button small right']) }}
	</div>
</div>

<a class="close-reveal-modal">&#215;</a>

{{-- alert for errors --}}
<div data-alert class="alert-box alert" style="display: none;">
  <a href="#" class="close">&times;</a>
</div>
