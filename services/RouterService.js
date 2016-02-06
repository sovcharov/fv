(function () {
    "use strict";
    angular.module('InvestorPanel').config(function ($stateProvider) {
        function authenticate($state, $q, $timeout, user) {
            if (user.id === 2) {
                return $q.when();
            }
            $timeout(function () {
                $state.go('login');
            });
            return $q.reject();
        }
        $stateProvider
            .state('login', {
                url: '/login',
                templateUrl: 'templates/login.html',
                controller: 'LogInPageController'
            })
            .state('main', {
                url: '/main',
                templateUrl: 'templates/main.html',
                controller: 'DataController',
                resolve: {authenticate: authenticate}
            });
    });
}());
