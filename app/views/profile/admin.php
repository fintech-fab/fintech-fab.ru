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
		</div>
	</div>
</div>

<div class="clear clearfix mt20"></div>
