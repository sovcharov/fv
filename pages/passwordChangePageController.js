(function () {
    "use strict";
    angular.module('InvestorPanel').controller("PasswordChangePageController", function ($scope, $state, $stateParams, $rootScope, $http, user) {

        $scope.newPassword = {
            first: '',
            second: ''
        };

        var regex = {
                password: /^[A-Za-z0-9.\-_*$]{5,}$/
            };

        $scope.passwordChange = function () {
            if (regex.password.test($scope.newPassword.first) && regex.password.test($scope.newPassword.second) && $scope.newPassword.first === $scope.newPassword.second) {
                var url = $rootScope.serverAddress + '/api/passwordChange/user/' + user.id + '/password/' + $scope.newPassword.first;
                $http.post(url).then(function () {
                    $rootScope.addInfoEvent('success', 'Пароль изменен');
                    $state.go('main.revenue');
                }, function () {
                    $rootScope.addInfoEvent('danger', 'Произошла ошибка');
                });
            } else {
                $rootScope.addInfoEvent('danger', 'Введены неверные данные');
            }
        };

    });
}());
