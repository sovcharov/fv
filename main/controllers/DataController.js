(function () {
    "use strict";
    angular.module("InvestorPanel").controller("DataController", function ($scope, $http) {
        var getStoresData, getData, dataInitOrDrop;
        $scope.bakeries = [];
        getData = function (store, date) {
            var data = {},
                i;
            data.date = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            data.store = store.id;
            $http({
                method: 'POST',
                url: 'data/getStoreData.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (dataReceived) {
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
            }).error(function () {
                for (i = 0; i < $scope.bakeries.length; i = i + 1) {
                    if ($scope.bakeries[i].number === store.id) {
                        $scope.bakeries[i].cash = "Ошибка";
                        $scope.bakeries[i].checks = 0;
                        $scope.bakeries[i].averageCheck = 0;
                    }
                }
            });
        };

        getStoresData = function (date, stores) {
            var i;
            for (i = 0; i < stores.length; i = i + 1) {
                getData(stores[i], date);
            }

        };

        dataInitOrDrop = function (stores) {
            var i;
            for (i = 0; i < stores.length; i = i + 1) {
                $scope.bakeries[i] = {};
                $scope.bakeries[i].number = stores[i].id;
                $scope.bakeries[i].address = stores[i].address;
                $scope.bakeries[i].cash = -1;
                $scope.bakeries[i].checks = 0;
                $scope.bakeries[i].averageCheck = 0;
            }
        };

        (function () {
            $http.get('data/getUserStores.php').success(function (data) {
                $scope.stores = data;
                dataInitOrDrop($scope.stores);
                var date = new Date();
                getStoresData(date, $scope.stores);
            });
        }());

        $scope.dateChanged = function (date) {
            dataInitOrDrop($scope.stores);
            getStoresData(date, $scope.stores);
        };
    });
}());
