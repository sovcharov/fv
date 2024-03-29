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
            // $rootScope.serverAddress = 'http://127.0.0.1:5555';//'http://fvolchek.net:5555';


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
                    if (user.id === 1) {
                        return true;
                    }
                    return false;
                },

                toReceiptsPage: function () {
                    if (user.id === 1) {
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
                    if (user.id === 1) {
                        return true;
                    }
                    return false;
                },

                toAdmin: function () {
                    if (this.toUsersManagementPage() || this.toUsersManagementKVPage()) {
                        return true;
                    }
                    return false;
                },

                toUsersManagementPage: function () {
                    if (user.id === 1 || user.id === 3) {
                        return true;
                    }
                    return false;
                },

                toUsersManagementKVPage: function () {
                    if (user.id === 1 || user.id === 25) {
                        return true;
                    }
                    return false;
                },

                toFV: function () {
                    if (this.toAdmin() || user.type === 4 || user.type === 5 || user.type === 0) {
                        return true;
                    }
                    return false;
                },

                toKV: function () {
                    if (this.toAdmin() || user.type === 6 || user.type === 5) {
                        return true;
                    }
                    return false;
                },

                to4s: function () {
                    if (user.id === 1 || user.id === 3 || user.type === 7) {
                        return true;
                    }
                    return false;
                }
            };
        });
}());
