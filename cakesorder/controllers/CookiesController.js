angular.module('FVCakes')
    .controller("CookiesController",  ['$cookies', '$scope', function ($cookies, $scope) {
        "use strict";
        // Retrieving a cookie
        $scope.model.user = $cookies.userID;
    }]);