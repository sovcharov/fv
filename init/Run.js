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

            $rootScope.commonDate = new Date(); //this date should be shared date for receipts, hourlystats and revenue pages.
            $rootScope.serverAddress = 'https://fvolchek.net:5555';//'http://127.0.0.1:5555';//'http://fvolchek.net:5555';
            $rootScope.serverAddress = 'http://127.0.0.1:5555';//'http://fvolchek.net:5555';


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

                toToolsPage: function () {
                    if (this.toAnalitics() || this.toManagersTool() || this.toAdmin()) {
                        return true;
                    }
                    return false;
                },

                toAnalitics: function () {
                    if (this.toHourlyStatsPage() || this.toReceiptsPage()) {
                        return true;
                    }
                    return false;
                },

                toHourlyStatsPage: function () {
                    if (user.id === 1 || user.id === 2 || user.id === 3 || user.id === 4 || user.id === 8 || user.type === 4) {
                        return true;
                    }
                    return false;
                },

                toReceiptsPage: function () {
                    if (user.id === 1 || user.id === 2 || user.id === 3 || user.id === 4 || user.id === 8 || user.type === 4) {
                        return true;
                    }
                    return false;
                },

                toManagersTool: function () {
                    if (this.toOrdersPage()) {
                        return true;
                    }
                    return false;
                },

                toOrdersPage: function () {
                    if (user.id === 1 || user.id === 2 || user.id === 3 || user.type === 4) {
                        return true;
                    }
                    return false;
                },

                toAdmin: function () {
                    if (this.toUsersManagementPage()) {
                        return true;
                    }
                    return false;
                },

                toUsersManagementPage: function () {
                    if (user.id === 1 || user.id === 3) {
                        return true;
                    }
                    return false;
                }
            };
        });
}());
