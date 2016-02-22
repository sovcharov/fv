(function () {
    "use strict";
    angular.module("InvestorPanel").directive('infoBox', function () {
        return {
            restrict: 'E',
            templateUrl: 'directives/infoBox.html',
            controller: 'InfoBoxController'
        };
    });
}());
