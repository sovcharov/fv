/*global angular*/
(function () {
    "use strict";
    angular.module('InvestorPanel').controller("UsersManagementPageController", function ($scope, $rootScope, $http, $cookies) {

        var getUsers,
            i,
            regex = {
                email: /^[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*@[A-Za-z0-9]+((([.\-_])[A-Za-z0-9]+)?)*\.[A-Za-z]{2,4}$/,
                firstName: /^[ A-Za-zА-ЯЁа-яё\-]{2,20}$/,
                lastName: /^[ A-Za-zА-ЯЁа-яё\-]{2,20}$/
            };

        $scope.newUser = {
            firstName: '',
            lastName: '',
            email: ''
        };

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

        $scope.addUser = function () {
            if (regex.email.test($scope.newUser.email) && regex.firstName.test($scope.newUser.firstName) && regex.lastName.test($scope.newUser.lastName)) {
                $scope.addingUser = true;
                var user = $scope.newUser,
                    url = $rootScope.serverAddress + '/api/user/' + parseInt($cookies.get('userID'), 10) + '/token/' + parseInt($cookies.get('token'), 10) + '/userFirstName/' + user.firstName + '/userLastName/' + user.lastName + '/userEmail/' + user.email;
                $http({
                    method: 'PUT',
                    url: url
                }).then(function (data) {
                    if (data.data[0][0]) {
                        $rootScope.addInfoEvent('success', 'Пользователь добавлен');
                        data.data[0][0].newElement = true;
                        $scope.users[$scope.users.length] = data.data[0][0];
                        $scope.newUser = {
                            firstName: '',
                            lastName: '',
                            email: ''
                        };
                    } else {
                        $rootScope.addInfoEvent('danger', 'Произошла ошибка');
                    }
                    $scope.addingUser = false;
                }, function () {
                    $scope.addingUser = false;
                    $rootScope.addInfoEvent('danger', 'Произошла ошибка');
                });

            } else {
                $rootScope.addInfoEvent('danger', 'Введены неверные данные');
            }

        };

    });
}());
