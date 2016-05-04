(function () {
    "use strict";
    angular.module('InvestorPanel').controller("OrdersPageController", function ($scope, $state, $stateParams, $rootScope, $http) {
        var getData;

        $scope.bakeryID = $stateParams.bakeryID || "1";
        $scope.order = {};
        // $scope.dateToday = new Date();

        // $rootScope.addLog("Receipts load " + $scope.bakeryID);

        $scope.changeBakery = function (id) {
            $state.go('main.orders', {bakeryID : id});
        };

        getData = function (bakery, date) {

            var url,
                dateForUrl;

            dateForUrl = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
            url = $rootScope.serverAddress + '/api/orders/store/' + bakery.id + '/date/' + dateForUrl;
            console.log(url);
            $http.get(url)
                .success(function (dataReceived) {
                    $scope.order.message = dataReceived;
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
