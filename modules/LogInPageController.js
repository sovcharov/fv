(function () {
    "use strict";
    angular.module('InvestorPanel').controller("LogInPageController", function ($scope, $cookies, $log, user) {
        var setCookies = function () {
            var user = $cookies.get('userID'),
                token = $cookies.get('token');
            $log.info(user + ' ' + token);
        };
    });
}());
