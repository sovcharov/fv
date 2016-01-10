angular.module("FVCakes")
    .directive('myTabs', function () {
        "use strict";
        return {
            restrict: 'E',
            templateUrl: 'directives/tabs.html'
        };
    });