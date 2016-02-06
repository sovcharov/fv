(function () {
    "use strict";
    angular.module('InvestorPanel', ['ui.bootstrap', 'ui.router']).value('user', {
        firstName: 'Sergei',
        lastName: 'Ovcharov',
        id: 2
    }).controller("MainController", function ($http, $state) {
        $state.go('main');
        //check for user to exist before enter main page
        // (function () {
        //     $http.get('data/checkUser.php').success(function (data) {
        //         if (!parseInt(data, 10)) {
        //             window.open("http://www.fvolchek.net", "_self", false);
        //             // window.location.assign("http://www.fvolchek.net");
        //         }
        //     });
        // }());
        //
        // $scope.addLog = function (action) {
        //     var data = {};
        //     data.action = action;
        //     $http({
        //         method: 'POST',
        //         url: 'services/addLog.php',
        //         data: data,
        //         headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        //     });
        // };
        // $scope.addLog("Main load");
    });
}());
