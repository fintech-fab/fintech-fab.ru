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

{{ Form::hidden('event_id') }}

<div class="row">
	<div class="large-3 large-centered columns">
		{{ Form::label('signal_id', 'Сигнал') }}
		<select name="signal_id" class="s2">
			@foreach($signals as $signal)
				<option value="{{ $signal->id }}">{{ $signal->signal_sid }}&nbsp;({{ $signal->name }})</option>
			@endforeach
		</select>
	</div>
</div>

<div class="row">
	<div class="large-1 large-offset-9 columns">
        <div class="switch">
            {{ Form::checkbox('flag_active', null, null, ['id' => 'flag_active']) }}
            {{ Form::label('flag_active') }}
        </div>
	</div>
	<div class="large-2 columns right">
		{{ Form::submit('Создать', ['id' => 'button-rule-create', 'class' => 'button small right']) }}
	</div>
</div>

{{-- close markup --}}
<a class="close-reveal-modal">&#215;</a>

{{-- alert for specific errors --}}
<div data-alert class="alert-box alert" style="display: none;">
  <a href="#" class="close">&times;</a>
</div>