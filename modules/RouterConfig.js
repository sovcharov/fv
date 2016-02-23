(function () {
    "use strict";
    angular.module('InvestorPanel').config(function ($stateProvider) {

        function authenticate($state, $q, $timeout, user, $http) {
            user.getAuthenticated($http);
            if (user.authenticated) {
                return $q.when();
            }
            $timeout(function () {
                $state.go('login');
            });
            return $q.reject();
        }

        function showLogin($q, user) {
            if (user.authenticated === false) {
                return $q.when();
            }
            return $q.reject();
        }

        function ordersShow($q, $rootScope) {
            if ($rootScope.accessToOrders()) {
                return $q.when();
            }
            return $q.reject();
        }

        function bakeryShow($q, $rootScope) {
            if ($rootScope.accessToBakeryPage()) {
                return $q.when();
            }
            return $q.reject();
        }

        $stateProvider
            .state('login', {
                url: '/login',
                templateUrl: 'templates/login.html',
                controller: 'LogInPageController',
                resolve: {showLogin: showLogin}
            })
            .state('main', {
                url: '/main',
                templateUrl: 'templates/main.html',
                controller: 'MainController',
                redirectTo: 'main.revenue',
                resolve: {authenticate: authenticate}
            })
            .state('main.revenue', {
                url: '^/revenue',
                templateUrl: 'templates/revenue.html',
                controller: 'DataController'
            })
            .state('main.orders', {
                url: '^/orders',
                templateUrl: 'templates/orders.html',
                controller: 'OrdersController',
                resolve: {ordersShow: ordersShow}
            })
            .state('main.hourlystats', {
                url: '^/hourlystats/{bakeryID:[0-9]}',
                templateUrl: 'pages/hourlyStats.html',
                controller: 'HourlyStatsPageController',
                resolve: {bakeryShow: bakeryShow}
            });
    });
}());
