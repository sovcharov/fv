angular.module("FVCakes")
	.run(function ($http, $rootScope, $interval) {
        "use strict";
        var i;
		$interval(function () {
			$http.get('services/checkUser.php').success(function (data3) {
				if (!data3[0]) {
                    window.location.assign("http://www.fvolchek.net");
                }
			});
		}, 10000);
		$rootScope.times = [];
		for (i = 8; i < 22; i += 1) {
			$rootScope.times.push(i + ":00");
		}
		$rootScope.weights = {};
		$http.get("data/weights.json").success(function (data) {
			$rootScope.weights = data;
		});
		$http.get('data/getcakes.php').success(function (data) {
			$rootScope.cakes = data;
		});
		$http.get('data/getstores.php').success(function (data) {
			$rootScope.stores = data;
		});
		$http.get('data/getUserInfo.php').success(function (data) {
			$rootScope.user = data;
		});
		$http.get('data/getUsers.php').success(function (data) {
			$rootScope.users = data;
		});
		$rootScope.orders = [];
		$rootScope.dataInit = function () {
			//THIS IS NEW COMPLEX INIT
			var index = $rootScope.orders.length;
			$rootScope.orders[index] = {};
			//head of order(common properties)
			$rootScope.orders[index].author = $rootScope.user.id;
			$rootScope.orders[index].customer = "";
			$rootScope.orders[index].customerNumber = "";
			$rootScope.orders[index].date = new Date();
			if ($rootScope.orders[index].date.getHours() < 15) {
				$rootScope.orders[index].date.setDate($rootScope.orders[index].date.getDate() + 1);
			} else {
                $rootScope.orders[index].date.setDate($rootScope.orders[index].date.getDate() + 2);
            }
			$rootScope.orders[index].address = $rootScope.user.store;
			$rootScope.orders[index].time = $rootScope.times[0];
			//1st cake properties
			$rootScope.orders[index].cakes = [];
			$rootScope.orders[index].cakes[0] = {};
			$rootScope.orders[index].cakes[0].cut = false;
			$rootScope.orders[index].cakes[0].qty = 1;
			$rootScope.orders[index].cakes[0].text = "";
			$rootScope.orders[index].cakes[0].comment = "";
			$rootScope.orders[index].cakes[0].cake = $rootScope.cakes[0].id;
			$rootScope.orders[index].cakes[0].weight = $rootScope.weights[0].weight;
			return index;
		};
	});