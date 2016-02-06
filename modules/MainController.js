(function () {
    "use strict";
    angular.module('InvestorPanel', ['ui.bootstrap', 'ui.router', 'ngCookies']).value('user', {
        firstName: '',
        lastName: '',
        id: 0,
        authenticated: false
    }).run(function (user, $http, $interval, $state, $log) {
        user.getAuthenticated = function ($http) {
            $http.get('data/checkUser.php').success(function (data) {
                if (!parseInt(data, 10)) {
                    user.authenticated = false;
                    $state.go('login');
                } else {
                    user.authenticated = true;
                    $state.go('main');
                }
                // $log.info(data);
            });
        };
        user.getAuthenticated($http);
        $interval(function () {
            user.getAuthenticated($http);
        }, 20000);
    }).controller("MainController", function ($scope, user) {
        $scope.authenticated = user.authenticated;

        //check for user to exist before enter main page
        // (function () {
        //     $http.get('data/checkUser.php').success(function (data) {
        //         if (!parseInt(data, 10)) {
        //             window.open("http://www.fvolchek.net", "_self", false);
        //             // window.location.assign("http://www.fvolchek.net");
        //         }
        //     });
        // }());
        //
        // $scope.addLog = function (action) {
        //     var data = {};
        //     data.action = action;
        //     $http({
        //         method: 'POST',
        //         url: 'services/addLog.php',
        //         data: data,
        //         headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        //     });
        // };
        // $scope.addLog("Main load");
    });
}());
