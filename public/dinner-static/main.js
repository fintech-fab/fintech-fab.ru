var countryApp = angular.module('DinnerOrder', []);
countryApp.controller('MenuItems', function ($scope, $http, $filter) {

	$scope.items = [];

	var date = new Date();
	date = $filter('date')(date.getTime(), 'yyyy-MM-dd');

	$http.get('/dinner/menuitems/' + date).success(function (response) {
		$scope.items = response;
	});

});