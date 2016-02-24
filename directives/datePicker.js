(function () {
    "use strict";
    angular.module("InvestorPanel").directive('datePicker', function () {
        return {
            restrict: 'E',
            templateUrl: 'directives/datePicker.html',
            controller: 'DateController'
        };
    });
}());
