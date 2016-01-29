(function () {
    "use strict";
    angular.module('InvestorPanel', ['ui.bootstrap']);
    angular.module('InvestorPanel').controller("MainController", function ($scope, $http) {

        //check for user to exist before enter main page
        (function () {
            $http.get('data/checkUser.php').success(function (data) {
                if (!data) {
                    window.open("http://www.fvolchek.net", "_self", false);
                    // window.location.assign("http://www.fvolchek.net");
                }
            });
        }());
    });
}());
