(function () {
    "use strict";
    angular.module('InvestorPanel', ['ui.bootstrap', 'ui.router']);
    angular.module('InvestorPanel').config(function ($stateProvider) {
        $stateProvider
            .state('login', {
                url: '/login',
                templateUrl: 'pages/login.html',
                controller: 'LogInPageController'
            });
    });
}());
