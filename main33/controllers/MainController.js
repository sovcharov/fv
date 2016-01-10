(function () {
    "use strict";
    angular.module('InvestorPanel', []);
    angular.module('InvestorPanel').controller("MainController", function ($scope, $http) {
        $scope.zero = [];
        $scope.bakeries = [
            {
                number: 1,
                address: 'Парашютная ул. д. 27 корп. 1',
                date: '28.08',
                receipts: 52333,
                checks: 122,
                averageCheck: 99,
                deepData: []
            },
            {
                number: 1,
                address: 'Парашютная ул. д. 27 корп. 1',
                date: '28.08',
                receipts: 52333,
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