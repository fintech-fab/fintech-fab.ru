

var countryApp = angular.module('GitHub', []);
countryApp.controller('Board', function ($scope, $http){

	$http.get('/github/api/issues/last').success(function(response) {
		$scope.lastIssues = response.data;
	});

	$http.get('/github/api/comments/last').success(function(response) {
		$scope.lastComments = response.data;
	});

	$http.get('/github/api/commits/last').success(function(response) {
		$scope.lastCommits = response.data;
	});

});