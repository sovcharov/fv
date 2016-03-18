(function () {
    "use strict";
    angular.module("InvestorPanel").controller("RevenuePageController", function ($rootScope, $scope, $http) {
        var getStoresData, getData, dataInitOrDrop, getAvailableStores;

        getData = function (bakery, date) {
            var url,
                dateForUrl;
            dateForUrl = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            url = $rootScope.serverAddress + '/api/storedata/store/' + bakery.id + '/date/' + dateForUrl;
            // console.log(url);
            $http.get(url)
                .success(function (dataReceived) {
                    bakery.cash = dataReceived.total || 0;
                    bakery.checks = dataReceived.checks;
                    if (dataReceived.checks) {
                        bakery.averageCheck = parseInt(bakery.cash, 10) / parseInt(bakery.checks, 10);
                    } else {
                        bakery.averageCheck = 0;
                    }
                }).error(function () {
                    $rootScope.addInfoEvent('danger', 'Ошибка загрузки', bakery.id + ' ' + bakery.address);
                    bakery.cash = 0;
                    bakery.checks = 0;
                    bakery.averageCheck = 0;
                });
        };

        getStoresData = function (date, bakeries) {
            var i;
            for (i = 0; i < bakeries.length; i = i + 1) { //< bakeries.length
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
            $http.get('server/getUserStores.php').success(function (data) {
                $rootScope.bakeries = data;
                dataInitOrDrop($rootScope.bakeries);
                var date = new Date();
                getStoresData(date, $rootScope.bakeries);
            });
        };

        if (!$rootScope.bakeries.length) {
            getAvailableStores();
        }

        $scope.dateChanged = function (date) {
            $rootScope.commonDate = date;
            dataInitOrDrop($rootScope.bakeries);
            getStoresData(date, $rootScope.bakeries);
        };
    });
}());
