(function () {
    "use strict";
    angular.module('InvestorPanel', ['ui.bootstrap', 'ui.router', 'ngCookies'])

        .value('user', {
            firstName: '',
            lastName: '',
            id: 0,
            type: null,
            authenticated: null
        })

        .run(function (user, $http, $interval, $state, $cookies, $rootScope) {

            var fillUserData = function () {
                user.type = parseInt($cookies.get('userType'), 10);
                user.id = parseInt($cookies.get('userID'), 10);
                user.firstName = $cookies.get('userName');
            };

            fillUserData();

            user.getAuthenticated = function ($http) {
                $http.get('data/checkUser.php').success(function (data) {
                    if (!parseInt(data, 10)) {
                        user.authenticated = false;
                        $rootScope.bakeries = [];
                        $state.go('login');
                    } else {
                        if (!user.authenticated) {
                            user.authenticated = true;
                            $state.go('main');
                        }
                        user.authenticated = true;
                    }
                });
            };
            $state.go('main');
            $interval(function () {
                user.getAuthenticated($http);
            }, 20000);
        });
}());
