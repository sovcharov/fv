(function () {
    "use strict";
    angular.module('InvestorPanel', ['ui.bootstrap']);
    angular.module('InvestorPanel').controller("MainController", function ($scope, $http) {
        $scope.getAds = function (venue, id) {
        };
        $scope.newWindow = function () {
            window.alert('huh');
        };
    });
}());