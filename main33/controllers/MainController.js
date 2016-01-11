(function () {
    "use strict";
    angular.module('InvestorPanel', []);
    angular.module('InvestorPanel').controller("MainController", function ($scope, $http) {
        $scope.zero = [];
        $scope.bakeries = [
            {
                number: 2,
                address: 'Светлановский 66',
                date: '11.01',
                cash: 58002,
                checks: 212,
                averageCheck: 199,
                deepData: []
            },
            {
                number: 1,
                address: 'Парашютная 27',
                date: '28.08',
                cash: 52333,
                checks: 122,
                averageCheck: 99,
                deepData: []
            },
            {
                number: 8,
                address: 'Энгельса 113',
                date: '11.01',
                cash: 582333,
                checks: 122,
                averageCheck: 99,
                deepData: []
            }
        ];
        $scope.getAds = function (venue, id) {
        };
        $scope.newWindow = function () {
            window.alert('huh');
        };
    });
}());