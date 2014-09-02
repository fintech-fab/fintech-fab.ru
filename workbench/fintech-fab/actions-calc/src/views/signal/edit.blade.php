{{ Form::model($signal, ['data-id' => $signal->id, 'id' => 'signal-update-form']) }}

{{ Form::hidden('id', $signal->id) }}

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
		<div class="name-field">
            {{ Form::label('signal_sid', 'Имя сигнала(signal_sid)') }}
            {{ Form::input('text', 'signal_sid') }}
            <small class="hide" id="signal_sid-error">Имя сигнала(уникально).</small>
        </div>
	</div>
</div>

<div class="row">
	<div class="large-12 columns">
		{{ Form::submit('Обновить', ['id' => 'signal-update-button', 'class' => 'button small right']) }}
	</div>
</div>

{{-- modal close markup --}}
<a class="close-reveal-modal">&#215;</a>

{{-- alert for errors --}}
<div data-alert class="alert-box alert" style="display: none;">
  <a href="#" class="close">&times;</a>
</div>
