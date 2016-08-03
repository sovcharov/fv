/*global angular*/
(function () {
    "use strict";
    angular.module('InvestorPanel').controller("UsersManagementPageController", function ($scope, $rootScope, $http, $cookies) {

        var getUsers,
            i;


        getUsers = function () {
            var url = $rootScope.serverAddress + '/api/users/user/' + parseInt($cookies.get('userID'), 10) + '/token/' + parseInt($cookies.get('token'), 10);
            $http.get(url).then(function (data) {
                $scope.users = data.data;
            }, function () {
                $rootScope.addInfoEvent('danger', 'Произошла ошибка');
            });
        };
        getUsers();

        $scope.deletePressed = function (user) {
            user.toDelete = true;
        };

        $scope.deleteCanceled = function (user) {
            user.toDelete = false;
        };

        $scope.deleteUser = function (user) {
            var url = $rootScope.serverAddress + '/api/user/' + parseInt($cookies.get('userID'), 10) + '/token/' + parseInt($cookies.get('token'), 10) + '/userToDelete/' + user.id;
            $http({
                method: 'DELETE',
                url: url
            }).then(function (data) {
                if (data.data[0][0].deleted) {
                    $rootScope.addInfoEvent('success', 'Пользователь удален');
                    for (i = 0; i < $scope.users.length; i += 1) {
                        if (user.id === $scope.users[i].id) {
                            $scope.users.splice(i, 1);
                        }
                    }
                } else {
                    $rootScope.addInfoEvent('danger', 'Произошла ошибка');
                }
            }, function () {
                $rootScope.addInfoEvent('danger', 'Произошла ошибка');
                user.toDelete = false;
            });
        };

    });
}());
