(function () {
    "use strict";
    angular.module('InvestorPanel').controller("HourlyStatsPageController", function ($scope, $state, $stateParams, $rootScope) {
        // $scope.bakeries = $rootScope.bakeries;

        $scope.bakeryID = $stateParams.bakeryID || 1;
        $scope.today = new Date();

        $scope.dateChanged = function (date) {
            $scope.today = date;
        };

        $scope.changeBakery = function (id) {
            $state.go('main.hourlystats', {bakeryID : id});
        };
    });
}());
