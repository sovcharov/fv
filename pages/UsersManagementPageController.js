/*global angular*/
(function () {
    "use strict";
    angular.module('InvestorPanel').controller("UsersManagementPageController", function ($scope, $rootScope, $http) {

        $scope.users = [
            {
                id: 1,
                firstName: "Vasiya",
                lastName: "Pupkin",
                email: "vasya@pupkin.com"
            },
            {
                id: 2,
                firstName: "Igor",
                lastName: "Popkin",
                email: "igor@pupkin.com"
            }
        ];

        var url = $rootScope.serverAddress + '/api/users';
        $http.get(url, { withCredentials: true }).then(function () {
            $rootScope.addInfoEvent('success', 'Сервер ответил');
        }, function () {
            $rootScope.addInfoEvent('danger', 'Произошла ошибка');
        });

    });
}());
