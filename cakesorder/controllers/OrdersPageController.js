angular.module('FVCakes')
    .controller("OrdersPageController", function ($rootScope, $http, $scope) {
        "use strict";
        var changeTypesOrders = function (order) {
                var i;
                for (i = 0; i < order.length; i += 1) {
                    order[i].id = Number(order[i].id);
                    order[i].dateCreated = order[i].dateCreated.substr(8, 2) + '.' + order[i].dateCreated.substr(5, 2) + '.' + order[i].dateCreated.substr(0, 4);
                    order[i].confirmedByBakery = Number(order[i].confirmedByBakery);
                    order[i].acceptedByPlant = Number(order[i].acceptedByPlant);
                    order[i].author = Number(order[i].author);
                    order[i].address = Number(order[i].address);
                    order[i].time = order[i].time.substr(0, 5);
                    if (order[i].time.substr(0, 1) === "0") {
                        order[i].time = order[i].time.substr(1, 4);
                    }
                }
            },
            changeTypesCakes = function (order) {
                var i;
                for (i = 0; i < order.cakes.length; i += 1) {
                    if (order.cakes[i].cut === "1") {
                        order.cakes[i].cut = true;
                    } else {
                        order.cakes[i].cut = false;
                    }
                    order.cakes[i].qty = Number(order.cakes[i].qty);
                }
            },
            getOrderedCakesFromServer = function (id, index) {
                var data = {};
                //$rootScope.ORDERSMASS=[];
                data.user = $rootScope.user.id;
                data.id = id;
                $http({
                    method: 'POST',
                    url: 'services/orderOpenCheck.php',
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .success(function (data2) {
                        $rootScope.ORDERSMASS = data2;
                        if (data2[0]) {
                            $http({
                                method: 'POST',
                                url: 'data/getOrderedCakes.php',
                                data: data,
                                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                            })
                                .success(function (data) {
                                    var i;
                                    for (i = 0; i < $rootScope.ordersMain.length; i += 1) {
                                        if ($rootScope.ordersMain[i].id === id) {
                                            $rootScope.ordersMain[i].cakes = data[0];
                                            changeTypesCakes($rootScope.ordersMain[i]);
                                            $rootScope.orders[$rootScope.orders.length] = $rootScope.ordersMain[i];
                                        }
                                    }
                                    $rootScope.changeTab($rootScope.orders, id);
                                });
                        } else {
                            $rootScope.addEvent(1, "Заблокирован", "Пользователь: " + data2[1] + " " + data2[2]);
                            //$rootScope.setMainTab();
                        }
                    });
            };
        $rootScope.getOrders = function (dateFrom, dateTo) {
            var data = {};
            data.dateFrom = dateFrom.getFullYear() + ':' + (dateFrom.getMonth() + 1) + ':' + dateFrom.getDate();
            data.dateTo = dateTo.getFullYear() + ':' + (dateTo.getMonth() + 1) + ':' + dateTo.getDate();
            $http({
                method: 'POST',
                url: 'data/getOrders.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function (data) {
                    $rootScope.ordersMain = data[0];
                    changeTypesOrders($rootScope.ordersMain);
                });
            $rootScope.dateCurrent = dateFrom;
            $rootScope.dateCurrent2 = dateTo;
        };
        $rootScope.dateCurrent = new Date();
        $rootScope.dateCurrent2 = new Date();
        $rootScope.dateCurrent2.setDate($rootScope.dateCurrent2.getDate() + 7);
        $rootScope.getOrders($rootScope.dateCurrent, $rootScope.dateCurrent2);
        $scope.getOrderedCakes = function (id, index) {
            $rootScope.getOrders($rootScope.dateCurrent, $rootScope.dateCurrent2);
            var alreadyInOrders = false,
                i;
            if ($rootScope.orders.length) {
                for (i = 0; i < $rootScope.orders.length; i += 1) {
                    if ($rootScope.orders[i].id === Number(id)) {
                        alreadyInOrders = true;
                    }
                }
            }
            if (!alreadyInOrders) {
                getOrderedCakesFromServer(id, index);
            }
        };
        $scope.getStoreName = function (id) {
            var i;
            for (i = 0; i < $rootScope.stores.length; i += 1) {
                if (Number($rootScope.stores[i].id) === Number(id)) {
                    return $rootScope.stores[i].name;
                }
            }
        };
        $scope.getOrderGiveAwayDate = function (date, time) {
            var checkType = typeof date;
            if (checkType === "object") {
                date = date.toJSON();
            }
            return date.substr(8, 2) + '.' + date.substr(5, 2) + '.' + date.substr(0, 4);// +' в '+time;
        };
        $scope.getOrderAuthor = function (author) {
            var i;
            for (i = 0; i < $rootScope.users.length; i += 1) {
                if (Number($rootScope.users[i].id) === Number(author)) {
                    return $rootScope.users[i].firstName + " " + $rootScope.users[i].lastName;
                }
            }
        };
        $scope.getBaker = function (author) {
            var i;
            for (i = 0; i < $rootScope.users.length; i += 1) {
                if (Number($rootScope.users[i].id) === Number(author)) {
                    return $rootScope.users[i].firstName + " " + $rootScope.users[i].lastName;
                }
            }
        };
    });