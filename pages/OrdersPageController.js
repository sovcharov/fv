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
            url = $rootScope.serverAddress + '/api/orders/store/' + bakery + '/date/' + dateForUrl;
            console.log(url);
            $http.get(url)
                .success(function (dataReceived) {
                    $scope.order.message = dataReceived;
                });
        };

        $scope.createOrder = function (bakeryID) {
            var i;
            getData(bakeryID, $rootScope.commonDate);
        };

        $scope.dateChanged = function (date) {
            $rootScope.commonDate = date;
        };
    });
}());
