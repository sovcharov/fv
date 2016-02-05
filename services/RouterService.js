(function () {
    "use strict";
    angular.module('InvestorPanel').config(function ($stateProvider) {
        $stateProvider
            .state('login', {
                url: '/login',
                templateUrl: 'templates/login.html',
                controller: 'LogInPageController'
            })
            .state('main', {
                url: '/main',
                templateUrl: 'templates/main.html',
                controller: 'DataController'
            });
    });
}());
