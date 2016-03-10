(function () {
    "use strict";
    angular.module('InvestorPanel').controller("MainController", function ($rootScope, $http, user, $cookies, $state) {

        $rootScope.bakeries = [];

        $rootScope.authenticated = function () {
            return user.authenticated;
        };

        $rootScope.user = user;

        $rootScope.addLog = function (action) {
            var url = $rootScope.serverAddress + '/api/log/user/' + user.id + '/action/' + action;
            $http.post(url);
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
    });
}());
