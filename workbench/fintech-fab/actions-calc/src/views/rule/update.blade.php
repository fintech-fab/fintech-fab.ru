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
                <option value="{{ $signal->id }}" @if($rule->signal_id == $signal->id) selected="selected" @endif>
                    {{ $signal->signal_sid }}&nbsp;({{ $signal->name }})
                </option>
            @endforeach
        </select>
	</div>
</div>

<div class="row">
	<div class="large-12 columns">
	{{ Form::submit('Обновить', ['id' => 'button-rule-update', 'class' => 'button small right']) }}
	</div>
</div>

{{-- modal close markup --}}
<a class="close-reveal-modal">&#215;</a>

{{-- alert for errors --}}
<div data-alert class="alert-box alert" style="display: none;">
  <a href="#" class="close">&times;</a>
</div>
