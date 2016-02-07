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
                controller: 'DataController',
                resolve: {authenticate: authenticate}
            });
    });
}());
