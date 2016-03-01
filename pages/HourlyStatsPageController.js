(function () {
    "use strict";
    angular.module('InvestorPanel').controller("HourlyStatsPageController", function ($scope, $state, $stateParams, $rootScope, $http) {
        // $scope.bakeries = $rootScope.bakeries;
        var getData, i;

        $scope.bakeryID = $stateParams.bakeryID || "1";
        $scope.dateToday = new Date();

        $scope.changeBakery = function (id) {
            $state.go('main.hourlystats', {bakeryID : id});
        };

        getData = function (bakery, date) {

            var data = {},
                dt,
                year = date.getFullYear(),
                month = date.getMonth(),
                day = date.getDate();
            data.date = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            dt = new Date(year, month, day);
            dt.setDate(dt.getDate() - 7);
            data.date2 = dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
            data.store = bakery.id;

            bakery.hourlystats = null;

            $http({
                method: 'POST',
                url: 'server/getHourlyStats.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (dataReceived) {
                bakery.hourlystats = dataReceived;

            });
        };

        for (i = 0; i < $rootScope.bakeries.length; i = i + 1) {
            if ($rootScope.bakeries[i].id === parseInt($scope.bakeryID, 10)) {
                getData($rootScope.bakeries[i], $scope.dateToday);
            }
        }

        $scope.getCurrentBakery = function (bakeryID) {
            for (i = 0; i < $rootScope.bakeries.length; i = i + 1) {
                if ($rootScope.bakeries[i].id === parseInt(bakeryID, 10)) {
                    return i;
                }
            }
        };

        $scope.getDateInStringFormat = function (date, offset) {
            var dt,
                year = date.getFullYear(),
                month = date.getMonth(),
                day = date.getDate();
            dt = new Date(year, month, day);
            dt.setDate(dt.getDate() - offset);
            year = dt.getFullYear();
            month = dt.getMonth() + 1;
            day = dt.getDate();
            if (month < 10) {
                month = "0" + month;
            }
            if (day < 10) {
                day = "0" + day;
            }
            return year + '-' + month + '-' + day;
        };

        $scope.getDateddMM = function (date, offset) {
            var dt,
                year = date.getFullYear(),
                month = date.getMonth(),
                day = date.getDate();
            dt = new Date(year, month, day);
            dt.setDate(dt.getDate() - offset);
            year = dt.getFullYear();
            month = dt.getMonth() + 1;
            day = dt.getDate();
            if (month < 10) {
                month = "0" + month;
            }
            if (day < 10) {
                day = "0" + day;
            }
            return day + '.' + month;
        };

        $scope.dateChanged = function (date) {
            $scope.dateToday = date;
            getData($scope.bakeries[$scope.getCurrentBakery($scope.bakeryID)], date);
        };
    });
}());
