(function () {
    "use strict";
    angular.module("InvestorPanel").controller("DataController", function ($scope, $http) {
        var getStoresData, getData;
        $scope.bakeries = [];
        getData = function (store, date, update) {
            var data = {},
                i;
            data.date = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            data.store = store.id;
            $http({
                method: 'POST',
                url: 'data/getStoreData.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function (dataReceived) {
                    if (update) {
                        for (i = 0; i < $scope.bakeries.length; i = i + 1) {
                            if ($scope.bakeries[i].number === store.id) {
                                $scope.bakeries[i].cash = dataReceived.cash;
                                $scope.bakeries[i].checks = dataReceived.checks;
                                if (dataReceived.checks) {
                                    $scope.bakeries[i].averageCheck = parseInt(dataReceived.cash, 10) / parseInt(dataReceived.checks, 10);
                                } else {
                                    $scope.bakeries[i].averageCheck = 0;
                                }
                            }
                        }
                    } else {
                        var indexLocal = $scope.bakeries.length;
                        $scope.bakeries[indexLocal] = {};
                        $scope.bakeries[indexLocal].number = store.id;
                        $scope.bakeries[indexLocal].address = store.address;
                        $scope.bakeries[indexLocal].cash = dataReceived.cash;
                        $scope.bakeries[indexLocal].checks = dataReceived.checks;
                        if (dataReceived.checks) {
                            $scope.bakeries[indexLocal].averageCheck = parseInt(dataReceived.cash, 10) / parseInt(dataReceived.checks, 10);
                        } else {
                            $scope.bakeries[indexLocal].averageCheck = 0;
                        }
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