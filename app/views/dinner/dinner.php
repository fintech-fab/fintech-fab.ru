<div class="container" ng-controller="MenuItems">

	<div class="row">
		<div class="col-md-11">
			<h3>Fintech-Fab: Работаем За Еду</h3>
		</div>

		<div class="col-md-12">
			<h4>За что работаем сегодня:</h4>
			<table class="table">
				<tr>
					<th>тайтл</th>
					<th>очков работы</th>
				</tr>
				<tr ng-repeat="item in items">
					<td>{{ item.title }}</td>
					<td>{{ item.price | currency:"" }}</td>
				</tr>
			</table>
		</div>


	</div>

	<div class="footer row small grey pull-right">
		Сделал за еду: <a href="https://github.com/kmarenov">kmarenov</a>
	</div>
</div>

