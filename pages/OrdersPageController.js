(function () {
    "use strict";
    angular.module('InvestorPanel').controller("OrdersPageController", function ($scope, $state, $stateParams, $rootScope, $http) {
        var getData, getForecast, getTest;

        $scope.bakeryID = $stateParams.bakeryID || "1";

        $rootScope.addLog("ORDERS load " + $scope.bakeryID);

        $scope.changeBakery = function (id) {
            $state.go('main.orders', {bakeryID : id});
        };

        getData = function (bakery, date) {
            $scope.order = 0;
            $scope.weather = 0;
            var url,
                dateForUrl;

            dateForUrl = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            url = $rootScope.serverAddress + '/api/orders/store/' + bakery + '/date/' + dateForUrl;
            $http.get(url)
                .success(function (dataReceived) {
                    $scope.order = dataReceived;
                    // getForecast(dateForUrl, bakery);
                });
            url = $rootScope.serverAddress + '/api/weather/date/' + dateForUrl;
            $http.get(url)
                .success(function (dataReceived) {
                    $scope.weather = dataReceived;
                });
        };

        getForecast = function (dateForUrl, bakery) {
            var url, i;
            for (i = 0; i < $scope.order.length; i += 1) {
                if($scope.order[i].code === 'УТ000000057') {
                    url = $rootScope.serverAddress + '/api/forecast/store/' + bakery + '/date/' + dateForUrl + '/item/' + $scope.order[i].code;
                    $http.get(url)
                        .success(function (dataReceived) {
                            $scope.forecast = dataReceived;
                        });
                }
            }
        };

        getTest = function () {
            var url, i, date, dateForUrl;
            url = $rootScope.serverAddress + '/api/test/';
            $http.get(url)
                .success(function (dataReceived) {
                    $scope.forecast = dataReceived;
                    for (i = 0; i < $scope.forecast.length; i += 1) {
                        date = $scope.forecast[i].date;
                        dateForUrl = date.slice(0,10);
                        url = $rootScope.serverAddress + '/api/forecast/store/' + 1 + '/date/' + dateForUrl + '/item/УТ000000023';
                        $http.get(url)
                            .success(function (dataReceived) {
                                var j;
                                for (j = 0; j < $scope.forecast.length; j += 1) {
                                    date = $scope.forecast[j].date;
                                    date = date.slice(0,10);
                                    if (date === dataReceived.date) {
                                        $scope.forecast[j].forecast = dataReceived.forecast;
                                        if($scope.forecast[j].forecast >= $scope.forecast[j].totalDemand) {
                                            $scope.forecast[j].incomeWouldBe = $scope.forecast[j].totalDemand - ($scope.forecast[j].forecast - $scope.forecast[j].totalDemand);
                                        } else {
                                            $scope.forecast[j].incomeWouldBe = $scope.forecast[j].forecast - ($scope.forecast[j].totalDemand - $scope.forecast[j].forecast);
                                        }
                                    }
                                }
                                $scope.totalIncome = {
                                    change: 0,
                                    withoutForecast: 0,
                                    withForecast: 0
                                };
                                for (j = 0; j < $scope.forecast.length; j += 1) {
                                    $scope.totalIncome.withoutForecast += $scope.forecast[j].income;
                                    $scope.totalIncome.withForecast += $scope.forecast[j].incomeWouldBe;
                                }
                                $scope.totalIncome.averageWithoutForecast = $scope.totalIncome.withoutForecast/$scope.forecast.length;
                                $scope.totalIncome.averageWithForecast = $scope.totalIncome.withForecast/$scope.forecast.length;
                                $scope.totalIncome.change = ($scope.totalIncome.withForecast-$scope.totalIncome.withoutForecast)/$scope.totalIncome.withoutForecast * 100;
                            });
                    }
                });

        };

        $scope.createOrder = function (bakeryID) {
            getData(bakeryID, $rootScope.ordersDate);
            getTest();
        };

        $scope.dateChanged = function (date) {
            $rootScope.ordersDate = date;
        };

        $scope.getLastSaleTime = function (item, weekAgo) {
            if (weekAgo === 1) {
                if (item.lossOne === 0) {
                    if (item.lastSaleWeekAgo) {
                        return item.lastSaleWeekAgo;
                    }
                    return '-';
                } else {
                    return '-';
                }
            } else {
                if (item.lossTwo === 0) {
                    if (item.lastSaleTwoWeeksAgo) {
                        return item.lastSaleTwoWeeksAgo;
                    }
                    return '-';
                } else {
                    return '-';
                }
            }
        };

        $scope.getNiceNumber = function (number) {
            if (parseInt(number, 10) === number) {
                return number;
            } else {
                return number.toFixed(2);
            }
        };

        $scope.addOrReduceQty = function (option, item) {
            option ? item.qtyToOrder += 1 : ((item.qtyToOrder > 0) ? item.qtyToOrder -= 1 : 0);
        };

        $scope.deleteItem = function (item) {
            var i;
            for (i = 0; i < $scope.order.length; i += 1) {
                if ($scope.order[i].code === item.code) {
                    $scope.order.splice(i, 1);
                }
            }
        };
    });
}());
