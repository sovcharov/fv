angular.module('ui.bootstrap')
.controller('DateController', function ($scope) {
  $scope.today = function() {
    $scope.dt = new Date();
  };
  $scope.today();

  $scope.clear = function () {
    $scope.dt = null;
  };

  // Disable weekend selection
  // $scope.disabled = function(date, mode) {
    // return ( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
  // };

  $scope.toggleMin = function() {
    //$scope.minDate = $scope.minDate ? null : new Date();
	// i wrote following and made first available date to pick - TOMORROW
	var date = new Date();
	var time = date.getHours();
	if(time<15){
		date.setDate(date.getDate()+1);
	}else date.setDate(date.getDate()+2);
	$scope.minDate = date;
  };
  $scope.toggleMin();

  $scope.open = function($event) {
    $event.preventDefault();
    $event.stopPropagation();

    $scope.opened = true;
  };

  $scope.dateOptions = {
    formatYear: 'yy',
    startingDay: 1
  };

  $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
  $scope.format = $scope.formats[2];
})

.controller('DateController2', function ($scope,$rootScope) {
	$scope.today = function() {
		$scope.dateFrom = new Date();
		$scope.dateTo = new Date();	
		$scope.dateFrom.setDate($scope.dateFrom.getDate()+0);
		$scope.dateTo.setDate($scope.dateTo.getDate()+7);
	};
	$scope.today();

	$scope.clear = function () {
		//$scope.dt = null;
	};

	$scope.toggleMin = function() {
	//$scope.minDate = $scope.minDate ? null : new Date();
	// i wrote following and made first available date to pick - TOMORROW
		var date = new Date();
		date.setDate(date.getDate()+1);
		$scope.minDate = date;
	};
	$scope.toggleMin();

	$scope.open = function($event) {
		$event.preventDefault();
		$event.stopPropagation();

		$scope.opened = true;
		$scope.opened2 = false;	
	};

	$scope.open2 = function($event) {
	$event.preventDefault();
	$event.stopPropagation();

	$scope.opened2 = true;
	$scope.opened = false;
	};

	$scope.dateOptions = {
	formatYear: 'yy',
	startingDay: 1
	};

	$scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
	$scope.format = $scope.formats[2];

	$scope.changeDateTo = function(){
		if($scope.dateTo<$scope.dateFrom) $scope.dateTo=$scope.dateFrom;
	};
});