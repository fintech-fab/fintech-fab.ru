<?php
/**
 * File _events.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @var FintechFab\ActionsCalc\Models\Event[] $events
 */
?>
<!-- buttons: search and add event -->
<div class="row">
	<div class="large-3 columns">
		<div class="row collapse postfix">
			<div class="large-9 columns search-field-wrap">
				<input id="search-event-text" name="search-event" type="text" placeholder="Искать события">
			</div>
			<div class="large-3 columns">
				<button id="search-event" class="button postfix"><i class="fi-magnifying-glass"></i></button>
			</div>
		</div>
	</div>
	<div class="large-3 columns">
		<button id="search-revert" class="button success tiny" style="display: none;"><i class="fi-loop"></i>&nbsp;к
			событиям
		</button>
	</div>
	<div class="large-6 columns">
		<ul class="button-group right">
			<!--<li>
				<a id="manage-chain" href="#" class="button secondary small right">
					Цепочкой&nbsp;<i class="fi-plus"></i>&nbsp;<i class="fi-plus"></i></a>
			</li>-->			<!-- TODO: add chained creation: event -> rules -> signal -->
			<li>
				<button data-reveal-id="modal-event-create" id="event-create" class="button small right"> Добавить
					событие <i class="fi-plus"></i></button>
			</li>
		</ul>
	</div>
</div><!-- /buttons: search and add event -->

<!-- modal event create -->
<div id="modal-event-create" class="reveal-modal small" data-reveal>
	<?php echo View::make('ff-actions-calc::event.create'); ?>
</div><!-- /modal create event--><!-- modal event update-->
<div id="modal-update-event" class="reveal-modal small" data-reveal></div><!-- /modal update event--><!-- modal search event -->

<!-- modal rule create -->
<div id="modal-rule-create" class="reveal-modal medium" data-reveal></div><!-- /modal rule update -->
<!-- modal rule update -->
<div id="modal-rule-update" class="reveal-modal medium" data-reveal></div><!-- /modal rule update -->

<!-- modal signal create -->
<div id="modal-signal-create" class="reveal-modal small" data-reveal>
	<?php echo View::make('ff-actions-calc::signal.create'); ?>
</div><!-- /modal signal create --><!-- modal signal create -->
<div id="modal-signal-udpate" class="reveal-modal small" data-reveal></div><!-- /modal signal create -->

<!-- modal auth profile -->
<div id="modal-auth-profile" class="reveal-modal small" data-reveal></div><!-- /modal auth profile -->

<!-- events table -->
<div id="events-table-container">
	<?php /** @noinspection PhpUndefinedMethodInspection */
	$events->setBaseUrl('/actions-calc/events/table'); ?>
	<?php echo View::make('ff-actions-calc::calculator._events_table', ['events' => $events]); ?>
</div><!-- /events table -->

<!-- event rules template -->
<div id="event-rules-template" style="display: none;">
	<div class="event-rule">
		<div class="large-4 columns">
			<input class="event-rule-name" name="event-rule-name" type="text" placeholder="имя">
		</div>
		<div class="large-2 columns">
			<select class="event-rule-operator" name="event-rule-operator" title="">
				<option value="OP_BOOL">bool</option>
				<option value="OP_GREATER">></option>
				<option value="OP_GREATER_OR_EQUAL">>=</option>
				<option value="OP_LESS"><</option>
				<option value="OP_LESS_OR_EQUAL"><=</option>
				<option value="OP_EQUAL">=</option>
				<option value="OP_NOT_EQUAL">!=</option>
			</select>
		</div>
		<div class="large-4 columns event-rule-value-wrap">
			<input class="event-rule-value" name="event-rule-value" type="text" placeholder="значение">
		</div>
		<div class="large-2 columns">
			<a href="#" class="button secondary tiny delete-event-rule"><i class="fi-x"></i></a>
		</div>
	</div>
	<div class="rule-condition-bool">
		<select class="condition-bool" title="">
			<option value="true">true</option>
			<option value="false">false</option>
		</select>
	</div>
</div><!-- /event rules template -->
