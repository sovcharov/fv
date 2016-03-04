(function () {
    "use strict";
    angular.module('InvestorPanel').controller("ReceiptsPageController", function ($scope, $state, $stateParams, $rootScope, $http) {
        var getData;

        $scope.bakeryID = $stateParams.bakeryID || "1";
        // $scope.dateToday = new Date();

        $rootScope.addLog("Receipts load " + $scope.bakeryID);

        $scope.changeBakery = function (id) {
            $state.go('main.receipts', {bakeryID : id});
        };

        getData = function (bakery, date) {

            var data = {};
            data.date = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            data.store = bakery.id;

            bakery.receipts = null;

            $http({
                method: 'POST',
                url: 'server/getReceipts.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (dataReceived) {
                bakery.receipts = dataReceived;

            });
        };

        (function () {
            var i;
            for (i = 0; i < $rootScope.bakeries.length; i = i + 1) {
                if ($rootScope.bakeries[i].id === parseInt($scope.bakeryID, 10)) {
                    getData($rootScope.bakeries[i], $rootScope.commonDate);
                }
            }
        }());

        $scope.getCurrentBakery = function (bakeryID) {
            var i;
            for (i = 0; i < $rootScope.bakeries.length; i = i + 1) {
                if ($rootScope.bakeries[i].id === parseInt(bakeryID, 10)) {
                    return i;
                }
            }
        };

        $scope.dateChanged = function (date) {
            $rootScope.commonDate = date;
            getData($scope.bakeries[$scope.getCurrentBakery($scope.bakeryID)], date);
        };
    });
}());
