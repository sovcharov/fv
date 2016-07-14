(function () {
    "use strict";
    angular.module('InvestorPanel').controller("PasswordChangePageController", function ($scope, $state, $stateParams, $rootScope, $http) {

        $scope.newPassword = {
            first: '',
            second: ''
        };

        var regex = {
                password: /^[A-Za-z0-9.\-_*$]{5,}$/
            };

        $scope.passwordChange = function () {
            if (regex.password.test($scope.newPassword.first) && regex.password.test($scope.newPassword.second) && regex.password.test($scope.newPassword.first) === regex.password.test($scope.newPassword.second)) {
                console.log('hey');
            } else {
                $rootScope.addInfoEvent('danger', 'Введены неверные данные');
            }
        };

    });
}());
