(function () {
    "use strict";
    angular.module("InvestorPanel").controller("DataController", function ($scope, $http) {
        var getStoresData, getData, dataInitOrDrop, getAvailableStores;
        $scope.bakeries = [];
        getData = function (bakery, date) {
            var data = {};
            data.date = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            data.store = bakery.id;
            $http({
                method: 'POST',
                url: 'data/getStoreData.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (dataReceived) {
                bakery.cash = dataReceived.cash;
                bakery.checks = dataReceived.checks;
                if (dataReceived.checks) {
                    bakery.averageCheck = parseInt(dataReceived.cash, 10) / parseInt(dataReceived.checks, 10);
                } else {
                    bakery.averageCheck = 0;
                }
            }).error(function () {
                bakery.cash = "Ошибка";
                bakery.checks = 0;
                bakery.averageCheck = 0;
            });
        };

        getStoresData = function (date, bakeries) {
            var i;
            for (i = 0; i < bakeries.length; i = i + 1) {
                getData(bakeries[i], date);
            }

        };

        dataInitOrDrop = function (bakeries) {
            var i;
            for (i = 0; i < bakeries.length; i = i + 1) {
                bakeries[i].cash = -1;
                bakeries[i].checks = 0;
                bakeries[i].averageCheck = 0;
            }
        };

        getAvailableStores = function () {
            $http.get('data/getUserStores.php').success(function (data) {
                $scope.bakeries = data;
                dataInitOrDrop($scope.bakeries);
                var date = new Date();
                getStoresData(date, $scope.bakeries);
            });
        };

        if (!$scope.bakeries.length) {
            getAvailableStores();
        }

        $scope.dateChanged = function (date) {
            dataInitOrDrop($scope.bakeries);
            getStoresData(date, $scope.bakeries);
        };
    });
}());
