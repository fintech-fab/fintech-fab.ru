<?php

?>
<script src="/js/ActionForAdmin.js" type="text/javascript"></script>
<div class="row">
	<div class="col-md-3">
		<img src="/assets/main/logo.png" height="100px" class="img" />
	</div>
	<div class="col-md-9">
		<h2 class="text-center">Страница администратора</h2>
	</div>
</div>

<div class="Roles">
	<div class="text-center">

		<?=
		Form::button('Показать таблицу ролей', array(
			'type'  => 'button',
			'class' => 'btn btn-primary buttonWithMargin',
			'id'    => 'btnRoles',
		));
		?>

	</div>
	<div class="row mt20" id="tableRoles">
		<div class="col-xs-10 col-xs-offset-1">
			<table class="table table-striped" id="tableUser"></table>
			<div id="message" class="row"></div>

			<ul id="UserPages"  style="display:none;">
				<span id="btnsPLeft"  style="display:none;">
					<li>
						<button id="btnPFirst" class="btn buttonWithMargin"  type="button">1</button>
					</li>
					<li>
						<button id="btnPLeft" class="btn buttonWithMargin" type="button">&laquo;-</button>
					</li>
					<li>...</li>
				</span>
				<li>
					<button id="btnP1" class="btn buttonWithMargin" type="button">1</button>
				</li>
				<li>
					<button id="btnP2" class="btn buttonWithMargin"  type="button">2</button>
				</li>
				<li>
					<button id="btnP3" class="btn buttonWithMargin" type="button">3</button>
				</li>
				<li>
					<button id="btnP4" class="btn buttonWithMargin" type="button">4</button>
				</li>
				<li>
					<button id="btnP5" class="btn buttonWithMargin" type="button">5</button>
				</li>
				<span id="btnsPRight" style="display:none;">
					<li>...</li>
					<li>
						<button id="btnPRight" class="btn buttonWithMargin" type="button">-&raquo;</button>
					</li>
					<li>
						<button id="btnPEnd" class="btn buttonWithMargin" type="button">10</button>
					</li>
				</span>
			</ul>
		</div>
	</div>
</div>

<div class="clear clearfix mt20"></div>
