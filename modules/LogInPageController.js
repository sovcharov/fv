(function () {
    "use strict";
    angular.module('InvestorPanel').controller("LogInPageController", function ($scope, $http, $log, user, $cookies) {
        $cookies.put('userID', 1);
        //$cookies.put('token', 1925484);
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
                $http({
                    method: 'POST',
                    url: 'services/logIn.php',
                    data: $scope.login,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (dataReceived) {
                    $log.log(dataReceived);
                    $scope.login = {
                        email: '',
                        password: ''
                    };
                })
            }
        };
    });
}());
