(function () {
    "use strict";
    angular.module('InvestorPanel').controller("MainController", function ($rootScope, $http, user, $cookies, $state) {

        $rootScope.bakeries = [];

        $rootScope.authenticated = function () {
            return user.authenticated;
        };

        $rootScope.user = user;

        $rootScope.addLog = function (action) {
            var url = $rootScope.serverAddress + '/api/log/user/' + user.id + '/action/' + action;
            $http.post(url);
        };

        $rootScope.addLog("Main load");

        var userDataCheck = function () {
            var url = $rootScope.serverAddress + '/api/userDataCheck/' + user.id,
                daysLeft;
            $http.get(url).then(function (response) {
                if (response.data[0]) {
                    daysLeft = (14 - response.data[0][0].diff);
                    if (daysLeft < 0) {
                        $rootScope.addInfoEvent('danger', 'Это ваш последний сеанс', 'или смените пароль сейчас', true);
                        $rootScope.addInfoEvent('danger', 'временный удален', 'Меню -> Сменить пароль', true);
                    } else {
                        $rootScope.addInfoEvent('danger', 'Внимание', 'используется временный пароль', true);
                        $rootScope.addInfoEvent('danger', 'до блокировки ' + daysLeft + ' дней', 'или Меню -> Сменить пароль', true);
                    }
                }
            }, function () {
                $rootScope.addInfoEvent('danger', 'Произошла ошибка', 'проверка пользователя');
            });
        };
        userDataCheck();

        $rootScope.exitApp = function () {
            $cookies.remove('token');
            $cookies.remove('userID');
            $cookies.remove('userType');
            $cookies.remove('userName');
            user.authenticated = false;
            $state.go('login');
            $rootScope.addLog("Exit");
        };
    });
}());
