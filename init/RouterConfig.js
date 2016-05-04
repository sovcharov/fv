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
            if ($rootScope.access.toOrdersPage()) {
                return $q.when();
            }
            return $q.reject();
        }

        function hourlyStatsShow($q, $rootScope) {
            if ($rootScope.access.toHourlyStatsPage()) {
                return $q.when();
            }
            return $q.reject();
        }

        function toolsShow($q, $rootScope) {
            if ($rootScope.access.toToolsPage()) {
                return $q.when();
            }
            return $q.reject();
        }

        function receiptsShow($q, $rootScope) {
            if ($rootScope.access.toReceiptsPage()) {
                return $q.when();
            }
            return $q.reject();
        }

        $stateProvider
            .state('login', {
                url: '/login',
                templateUrl: 'pages/login.html',
                controller: 'LogInPageController',
                resolve: {showLogin: showLogin}
            })
            .state('main', {
                url: '/main',
                templateUrl: 'pages/main.html',
                controller: 'MainController',
                redirectTo: 'main.revenue',
                resolve: {authenticate: authenticate}
            })
            .state('main.revenue', {
                url: '^/revenue',
                templateUrl: 'pages/revenue.html',
                controller: 'RevenuePageController'
            })
            .state('main.tools', {
                url: '^/tools',
                templateUrl: 'pages/tools.html',
                controller: 'ToolsPageController',
                resolve: {toolsShow: toolsShow}
            })
            .state('main.orders', {
                url: '^/orders/{bakeryID:[0-9]}',
                templateUrl: 'pages/orders.html',
                controller: 'OrdersPageController',
                resolve: {ordersShow: ordersShow}
            })
            .state('main.hourlystats', {
                url: '^/hourlystats/{bakeryID:[0-9]}',
                templateUrl: 'pages/hourlyStats.html',
                controller: 'HourlyStatsPageController',
                resolve: {hourlyStatsShow: hourlyStatsShow}
            })
            .state('main.receipts', {
                url: '^/receipts/{bakeryID:[0-9]}',
                templateUrl: 'pages/receipts.html',
                controller: 'ReceiptsPageController',
                resolve: {receiptsShow: receiptsShow}
            });
    });
}());
