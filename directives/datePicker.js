(function () {
    "use strict";
    angular.module("InvestorPanel").directive('datePicker', function () {
        return {
            restrict: 'E',
            templateUrl: 'templates/datePicker.html',
            controller: 'DateController'
        };
    });
}());
