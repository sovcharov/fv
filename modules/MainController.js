(function () {
    "use strict";
    angular.module('InvestorPanel', ['ui.bootstrap', 'ui.router', 'ngCookies']).value('user', {
        firstName: '',
        lastName: '',
        id: 0,
        authenticated: null
    }).run(function (user, $http, $interval, $state) {
        user.getAuthenticated = function ($http) {
            $http.get('data/checkUser.php').success(function (data) {
                if (!parseInt(data, 10)) {
                    user.authenticated = false;
                    $state.go('login');
                } else {
                    user.authenticated = true;
                    $state.go('main');
                }
            });
        };
        user.getAuthenticated($http);
        $interval(function () {
            user.getAuthenticated($http);
        }, 20000);
    }).controller("MainController", function ($rootScope, user) {
        $rootScope.authenticated = function () {
            return user.authenticated;
        };

        // $scope.addLog = function (action) {
        //     var data = {
        //         action: action
        //     };
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
