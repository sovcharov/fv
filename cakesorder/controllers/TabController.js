angular.module("FVCakes")
    .controller("TabController", function ($rootScope, $scope) {
        "use strict";
        var tab = -1;
        this.setTab = function (value) {
            tab = value;
        };
        this.isSet = function (value) {
            return tab === value;
        };
        $rootScope.closeTab = function (value) {
            $rootScope.orders.splice(value, 1);
            this.setMainTab();
        };
        this.setMainTab = function () {
            this.setTab(-1);
        };
        $rootScope.setMainTab = function () {
            tab = -1;
        };
        $rootScope.changeTab = function (orders, id) {
            var i;
            for (i = 0; i < orders.length; i += 1) {
                if (orders[i].id === id) {
                    tab = i;
                }
            }
        };
    });