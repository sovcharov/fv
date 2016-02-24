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
                $http.get('server/checkUser.php').success(function (data) {
                    if (!parseInt(data, 10)) {
                        user.authenticated = false;
                        $rootScope.bakeries = [];
                        $state.go('login');
                    } else {
                        if (!user.authenticated) {
                            user.authenticated = true;
                            $state.go('main.revenue');
                        }
                        user.authenticated = true;
                    }
                });
            };

            $state.go('main.revenue');

            $interval(function () {
                user.getAuthenticated($http);
            }, 20000);

            //this function is needed for redirect to work (in our case when you hit main you have to redirect to main.revenue state)
            $rootScope.$on('$stateChangeStart', function (evt, to, params) {
                if (to.redirectTo) {
                    evt.preventDefault();
                    $state.go(to.redirectTo, params);
                }
            });

            $rootScope.access = {

                toOrdersPage: function () {
                    if (user.type === 1 || user.id === 1) {
                        return true;
                    }
                    return false;
                },

                toHourlyStatsPage: function () {
                    if (user.type === 1 || user.id === 1) {
                        return true;
                    }
                    return false;
                },

                toToolsPage: function () {
                    if (user.type === 1 || user.id === 1) {
                        return true;
                    }
                    return false;
                },

                toAdmin: function () {
                    if (user.id === 1) {
                        return true;
                    }
                    return false;
                }
            };
        });
}());
