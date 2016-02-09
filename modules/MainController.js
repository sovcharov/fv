(function () {
    "use strict";
    angular.module('InvestorPanel').controller("MainController", function ($rootScope, $http, user, $cookies, $state) {

        $rootScope.bakeries = [];

        $rootScope.authenticated = function () {
            return user.authenticated;
        };

        $rootScope.user = user;

        $rootScope.addLog = function (action) {
            var data = {
                action: action
            };
            $http({
                method: 'POST',
                url: 'services/addLog.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            });
        };

        $rootScope.addLog("Main load");

        $rootScope.exitApp = function () {
            $cookies.remove('token');
            $cookies.remove('userID');
            $cookies.remove('userType');
            $cookies.remove('userName');
            user.authenticated = false;
            $state.go('login');
            $rootScope.addLog("Exit");
        };

        $rootScope.accessToOrders = function () {
            if (user.type === 1 || user.id === 1) {
                return true;
            }
            return false;
        };
    });
}());
