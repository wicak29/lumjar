var app = angular.module('mainApp', ['ngRoute','ngIdle'])

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

app.config(['KeepaliveProvider', 'IdleProvider', function(KeepaliveProvider, IdleProvider) {
	IdleProvider.idle(5);
	IdleProvider.timeout(5);
	KeepaliveProvider.interval(10);
}]);

app.run(function(Idle){
	Idle.watch();
})

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
app.controller('homePage', function($scope, $http, $timeout, $rootScope, Idle) {
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

	$scope.$on('IdleStart', function() {
        console.log('idle start')
    });

    $scope.$on('IdleWarn', function(e, countdown) {
        // follows after the IdleStart event, but includes a countdown until the user is considered timed out
        // the countdown arg is the number of seconds remaining until then.
        // you can change the title or display a warning dialog from here.
        // you can let them resume their session by calling Idle.watch()
        console.log('idle warn')
    });

    $scope.$on('IdleTimeout', function() {
        // the user has timed out (meaning idleDuration + timeout has passed without any activity)
        // this is where you'd log them
        console.log('idle TO')
    });

    $scope.$on('IdleEnd', function() {
        // the user has come back from AFK and is doing stuff. if you are warning them, you can use this to hide the dialog
        console.log('idle End')
    });

    $scope.$on('Keepalive', function() {
        // do something to keep the user's session alive
    });
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