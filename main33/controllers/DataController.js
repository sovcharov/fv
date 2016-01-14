(function () {
    "use strict";
    angular.module("InvestorPanel").controller("DataController", function ($scope, $http) {
        var getStoresData, getData;
        $scope.bakeries = [];
        getData = function (store, date, update) {
            var data = {};
            data.date = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            console.log(data.date);
            data.store = store.id;
            $http({
                method: 'POST',
                url: 'data/getStoreData.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function (dataReceived) {
                    if (update) {
                        update = true;
                    } else {
                        var indexLocal = $scope.bakeries.length;
                        $scope.bakeries[indexLocal] = {};
                        $scope.bakeries[indexLocal].number = store.id;
                        $scope.bakeries[indexLocal].address = store.address;
                        $scope.bakeries[indexLocal].cash = dataReceived.cash;
                        $scope.bakeries[indexLocal].checks = dataReceived.checks;
                        $scope.bakeries[indexLocal].averageCheck = parseInt(dataReceived.cash, 10) / parseInt(dataReceived.checks, 10);
                    }
                });
        };
        getStoresData = function (date, stores) {
            var i, update = false;
            if ($scope.bakeries.length) {
                update = true;
            }
            for (i = 0; i < stores.length; i = i + 1) {
                getData(stores[i], date, update);
            }
        };
        
        (function () {
            $http.get('data/getUserStores.php').success(function (data) {
                $scope.stores = data;
                var date = new Date();
                getStoresData(date, $scope.stores);
            });
        }());

        $scope.dateChanged = function (date) {
            getStoresData(date, $scope.stores);
        };
    });
}());