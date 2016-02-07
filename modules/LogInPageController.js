(function () {
    "use strict";
    angular.module('InvestorPanel').controller("LogInPageController", function ($scope, $http, $log, user, $cookies) {
        $cookies.put('userID',1);
        $cookies.put('token',1925484);
        if (user.authenticated) {
            user.getAuthenticated($http);
        }
        var regex = {
            email: /^[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*@[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*\.[A-Za-z]{2,4}$/,
            password: /^[A-Za-z0-9.\-_*$]{5,}$/
        };
        $scope.login = {
            email: '',
            password: ''
        };
        $scope.formSubmit = function () {
            if (regex.email.test($scope.login.email) && regex.password.test($scope.login.password)) {
                $log.log('yahoo');
                return 0;
                // var data = $scope.login
                // $http({
                //     method: 'POST',
                //     url: 'data/logIn.php',
                //     data: data,
                //     headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                // }).success(function (dataReceived) {
                //     bakery.cash = dataReceived.cash;
                //     bakery.checks = dataReceived.checks;
                //     if (dataReceived.checks) {
                //         bakery.averageCheck = parseInt(dataReceived.cash, 10) / parseInt(dataReceived.checks, 10);
                //     } else {
                //         bakery.averageCheck = 0;
                //     }
                // })
                // $cookies.put('userID',''),
                // $cookies.put('token');
                // user.getAuthenticated($http);
            }
        };
    });
}());
