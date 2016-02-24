(function () {
    "use strict";
    angular.module('InvestorPanel').controller("LogInPageController", function ($rootScope, $scope, $http, user, $cookies, $state) {

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

        $scope.logIn = function () {
            if (regex.email.test($scope.login.email) && regex.password.test($scope.login.password)) {
                $http({
                    method: 'POST',
                    url: 'server/logIn.php',
                    data: $scope.login,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).success(function (dataReceived) {
                    if (dataReceived) {
                        var date = new Date();
                        date.setDate(date.getDate() + 1);
                        $cookies.put('token', dataReceived.token, {'expires': date});
                        $cookies.put('userID', dataReceived.userID, {'expires': date});
                        $cookies.put('userType', dataReceived.userType, {'expires': date});
                        $cookies.put('userName', dataReceived.firstName, {'expires': date});
                        user.authenticated = true;
                        $state.go('main.revenue');
                        $scope.login = {
                            email: '',
                            password: ''
                        };
                        user.id = dataReceived.userID;
                        user.type =  dataReceived.userID;
                        user.firstName = dataReceived.firstName;
                        user.lastName = dataReceived.lastName;
                    } else {
                        $scope.login = {
                            email: '',
                            password: ''
                        };
                        $rootScope.addInfoEvent(1, 'Введены неверные данные');
                    }
                });
            } else {
                $scope.login = {
                    email: '',
                    password: ''
                };
                $rootScope.addInfoEvent(1, 'Введены неверные данные');
            }
        };
    });
}());
