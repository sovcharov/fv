(function () {
    "use strict";
    angular.module('InvestorPanel').controller("BakeryPageController", function ($scope, $stateParams) {
        $scope.bakeryID = $stateParams.bakeryID || 1;
    });
}());
