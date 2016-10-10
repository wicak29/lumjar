var app = angular.module('mainApp', ['ngRoute'])

app.config(['$routeProvider', function($routeProvider, $window){
	$routeProvider
	.when('/', {
		templateUrl: 'main.html',
		controller: 'mainPage'
	})
	.when('/home', {
		templateUrl: 'home.html',
		controller: 'homePage'
	})
}])

app.controller('mainPage', function($scope, $http, $timeout, $interval) {
	$scope.flag = 0
	$scope.reloadData = function(){
		$http.get('getData.php', {}).success(function(data, status){
			// $scope.data = data
			// console.log(data)
		
			if ($scope.flag == 0 ) {
				$scope.data = data
				$scope.data.status = 404
				$scope.time = data.time
				$scope.flag = 1
				// console.log(data)
			}else{
				if ($scope.time != data.time) {
					$scope.data = data
					$scope.time = data.time
					$scope.now = data.pin
					console.log(data)
				}
			}
		})
	}
	var timer
	$scope.refresh = function() {
		timer = $interval($scope.reloadData, 2000)
	}
	$scope.refresh()
	$scope.$on('$destroy', function(){
    	if (timer) {
    		$interval.cancel(timer);   
    	}
	});
	// $scope.reloadData()
});
app.controller('homePage', function($scope, $http, $timeout) {
	$scope.reloadData = function(){
		$http.get('getAll.php', {}).success(function(data, status){
			$scope.data = data
			// console.log(data)
		})
		$http.get('getData.php', {}).success(function(data, status){
			$scope.user = data
			// console.log(data)
		})
	}
	$scope.reloadData()
});