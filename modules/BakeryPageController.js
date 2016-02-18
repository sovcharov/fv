(function () {
    "use strict";
    angular.module('InvestorPanel').controller("BakeryPageController", function ($scope, $stateParams, $rootScope) {
        // $scope.bakeries = $rootScope.bakeries;

        $scope.bakeryID = $stateParams.bakeryID || 1;
        $scope.today = new Date();
        $scope.yeserday = new Date();
        $scope.sevenDaysAgo = new Date();

        $scope.dateChanged = function (date) {
            $scope.today = date;
        };
    });
}());
