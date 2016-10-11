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
	.when('/nfc_data/:id', {
		templateUrl: 'loginNFC.html',
		controller: 'nfcControl'
	})
	.when('/register',{
		templateUrl: 'register.php',
		controller: 'register'
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
app.controller('homePage', function($scope, $http, $timeout, $rootScope) {
	$scope.reloadData = function(){
		$http.get('getAll.php', {}).success(function(data, status){
			$scope.data = data
			// console.log(data)
		})
		$http.get('getData.php', {}).success(function(data, status){
			$rootScope.user = data
			// console.log(data)
		})
	}
	$scope.reloadData()
});
app.controller('nfcControl', function($scope, $http, $routeParams, $rootScope) {
	var data = $routeParams.id
	$http({
	    method: 'POST',
	    url: 'nfcAuth.php',
	    data: {"data": data},
	    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	}).success(function(data, status){
		// $scope.data = data
		$rootScope.data = data
	})
});
app.controller('register', function($scope, $http, $routeParams) {
	$scope.submit = function(){
		$http({
		    method: 'POST',
		    url: 'userRegister.php',
		    data: $scope.data,
		    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).success(function(data, status){
			$scope.res = data
		})
	}
});