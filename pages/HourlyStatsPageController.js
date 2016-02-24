(function () {
    "use strict";
    angular.module('InvestorPanel').controller("HourlyStatsPageController", function ($scope, $state, $stateParams, $rootScope, $http) {
        // $scope.bakeries = $rootScope.bakeries;
        var getData, i;

        $scope.bakeryID = $stateParams.bakeryID || "1";
        $scope.dateToday = new Date();

        $scope.dateChanged = function (date) {
            $scope.dateToday = date;
        };

        $scope.changeBakery = function (id) {
            $state.go('main.hourlystats', {bakeryID : id});
        };

        getData = function (bakery, date) {
            var data = {};
            data.date = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            data.store = bakery.id;


            // return;

            $http({
                method: 'POST',
                url: 'server/getHourlyStats.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (dataReceived) {
                bakery.hourlystats = dataReceived;

            }).error(function () {

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
            var month = date.getMonth() + 1;
            if (month < 10) {
                month = "0" + month;
            }
            return date.getFullYear() + '-' + month + '-' + (date.getDate() - offset);
        };

        $scope.getDateddMM = function (date, offset) {
            var month = date.getMonth() + 1;
            if (month < 10) {
                month = "0" + month;
            }
            return (date.getDate() - offset) + '.' + month;
        };
    });
}());
