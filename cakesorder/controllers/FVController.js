angular.module('FVCakes', ['ui.bootstrap'])
    .controller("FVController", function ($rootScope, $http, $scope) {
        "use strict";
        $scope = $rootScope;
        var checkTime = function (dateOrder) {
                var dateCheckGiveAway = Number(dateOrder.substr(0, 4) + dateOrder.substr(5, 2) + dateOrder.substr(8, 2)),
                    date = new Date(),
                    dateCheckNow,
                    dateDifference;
                dateCheckNow =	date.toJSON();
                dateCheckNow = Number(dateCheckNow.substr(0, 4) + dateCheckNow.substr(5, 2) + dateCheckNow.substr(8, 2));
                dateDifference = dateCheckGiveAway - dateCheckNow;
                if ((dateDifference < 2 && date.getHours() > 14) || dateDifference < 1) {
                    return false;
                } else {
                    return true;
                }
            },
            getDateString = function (dateOrder) {
                var checkType = typeof dateOrder,
                    monthStr,
                    dayStr;
                if (checkType === "object") {
                    monthStr = (Number(dateOrder.getMonth() + 1) > 9 ? (dateOrder.getMonth() + 1) : "0" + (dateOrder.getMonth() + 1));
                    dayStr = (Number(dateOrder.getDate()) > 9 ? (dateOrder.getDate()) : "0" + (dateOrder.getDate()));
                    return dateOrder.getFullYear() + '-' + monthStr + '-' + dayStr;
                } else {
                    return dateOrder;
                }
            };
        $scope.saveAndPlace = function (data, index) {
            data.dateStr = getDateString(data.date);
            var tempDate = new Date();
            $scope.orders[index].tempID = tempDate.valueOf();
            if (!checkTime(data.dateStr)) {
                $scope.addEvent(1, "Неверное время");
            } else {
                $http({
                    method: 'POST',
                    url: 'data/orderSave.php',
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .success(function (dataOut) {
                        $scope.addEvent(0, "Cохранен");
                        if ($scope.orders[index].tempID) {
                            if ($scope.orders[index].tempID === tempDate.valueOf()) {
                                if (!$scope.orders[index].id) {
                                    $scope.orders[index].id = dataOut[0];
                                }
                            }
                        }
                        $rootScope.getOrders($rootScope.dateCurrent, $rootScope.dateCurrent2);
                    });
                return $scope.orders[index].id;
            }
        };
        $scope.orderPlace = function (index) {
            if (!$scope.orders[index].id) {
                $scope.saveAndPlace($scope.orders[index], index);
            }
            var dateStr = getDateString($scope.orders[index].date),
                data = $scope.orders[index];
            if (!checkTime(dateStr)) {
                $scope.addEvent(1, "Неверное время");
            } else {
                $http({
                    method: 'POST',
                    url: 'data/orderPlace.php',
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .success(function (data2) {
                        $scope.orders[index].confirmedByBakery = data2[0];
                        (data2[0]) ? $scope.addEvent(0, "Проведен") : $scope.addEvent(0, "Отозван");
                    });
            }
        };
        $scope.exitApp = function () {
            $http.get('exitapp.php');
            window.location.assign("http://www.fvolchek.net");
        };
        $scope.addOrReduceQty = function (option, cake, index) {
            option ? $scope.orders[index].cakes[cake].qty += 1 : (($scope.orders[index].cakes[cake].qty > 1) ? $scope.orders[index].cakes[cake].qty -= 1 : 0);
        };
        $scope.addNewCake = function (index) {
            var arLength = $scope.orders[index].cakes.length;
            $scope.orders[index].cakes[arLength] = {};
            $scope.orders[index].cakes[arLength].cut = false;
            $scope.orders[index].cakes[arLength].qty = 1;
            $scope.orders[index].cakes[arLength].weight = $scope.weights[0].weight;
            $scope.orders[index].cakes[arLength].cake = $scope.cakes[0].id;
            $rootScope.orders[index].cakes[arLength].text = "";
            $rootScope.orders[index].cakes[arLength].comment = "";
        };
        $scope.deleteCake = function (cake, index) {
            $scope.orders[index].cakes.length - 1 ? $scope.orders[index].cakes.splice(cake, 1) : 0;
        };
        $scope.getUserName = function (id) {
            var i;
            for (i = 0; i < $scope.users.length; i += 1) {
                if ($scope.users[i].id === id) {
                    return $scope.users[i].firstName + " " + $scope.users[i].lastName;
                }
            }
        };
        $scope.checkIfCantChange = function (author) {
            if (author === $rootScope.user.id || $rootScope.user.type === 1 || $rootScope.user.type === 4) {
                return false;
            } else {
                return true;
            }
        };
        $scope.chekUserTypeIsBaker = function () {
            return ($rootScope.user.type === 3) ? true : false;
        };
        $scope.chekUserTypeIsAdmin = function () {
            return ($rootScope.user.type === 1) ? true : false;
        };
        $scope.chekUserTypeIsManager = function () {
            return ($rootScope.user.type === 4) ? true : false;
        };
        $scope.orderAcceptByPlant = function (index) {
            var data = {};
            data.acceptedByPlant = $scope.orders[index].acceptedByPlant;
            data.id = $scope.orders[index].id;
            data.user = $rootScope.user.id;
            $http({
                method: 'POST',
                url: 'data/orderAcceptByPlant.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function (data2) {
                    $scope.orders[index].acceptedByPlant = data2[0];
                    $scope.orders[index].acceptedBy = data2[1];
                    (data2[0]) ? $scope.addEvent(0, "Принят") : $scope.addEvent(0, "Отозван");
                });
        };
        $scope.deletePressed = function (index) {
            $scope.orders[index].deletePressed = true;
        };
        $scope.deleteNotConfirmed = function (index) {
            $scope.orders[index].deletePressed = false;
        };
        $scope.orderDelete = function (index) {
            var data = {};
            data.id = $scope.orders[index].id;
            $http({
                method: 'POST',
                url: 'data/orderDelete.php',
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function (data2) {
                    $scope.orders.splice(index, 1);
                    var i;
                    for (i = 0; i < $scope.ordersMain.length; i += 1) {
                        if ($scope.ordersMain[i].id === data.id) {
                            $scope.ordersMain.splice(i, 1);
                        }
                    }
                    $scope.addEvent(0, "Удален");
                });
        };
        $scope.closeClicked = function (index, option) {
            if (!$scope.orders[index].orderChanged) {
                option = 2;
            }
            if (option === 1) {
                $scope.orders[index].closeClicked = true;
            } else if (option === 2) {
                $scope.orders[index].closeClicked = false;
                $scope.closeTab(index);
            } else {
                $scope.orders[index].closeClicked = false;
            }
        };
        $scope.orderChanged = function (index) {
            $scope.orders[index].orderChanged = true;
        };
        $scope.orderChangedClear = function (index) {
            $scope.orders[index].orderChanged = false;
        };
        $scope.chekEnteredName = function (index) {
            var regExpr = /^[А-Яа-я.,\s]{0,25}$/;
            if (!regExpr.test($scope.orders[index].customer)) {
                $scope.orders[index].customer = $scope.orders[index].customer.substring(0, $scope.orders[index].customer.length - 1);
            }
        };
        $scope.chekEnteredNumber = function (index) {
            var regExpr = /^\+?\d{0,11}$/;
            if (!regExpr.test($scope.orders[index].customerNumber)) {
                $scope.orders[index].customerNumber = $scope.orders[index].customerNumber.substring(0,  $scope.orders[index].customerNumber.length - 1);
            }
        };
        $scope.chekEnteredCakeText = function (cakeindex, index) {
            var regExpr = /^[А-Яа-яA-Za-z.,"\d\+\-=_:;\?\s!@№()#$%&^*]{0,200}$/;
            if (!regExpr.test($scope.orders[index].cakes[cakeindex].text)) {
                $scope.orders[index].cakes[cakeindex].text = $scope.orders[index].cakes[cakeindex].text.substring(0,  $scope.orders[index].cakes[cakeindex].text.length - 1);
            }
        };
        $scope.chekEnteredComment = function (cakeindex, index) {
            var regExpr = /^[А-Яа-яA-Za-z.,"\d\+\-=_:;\?\s!@№()#$%&^*]{0,200}$/;
            if (!regExpr.test($scope.orders[index].cakes[cakeindex].comment)) {
                $scope.orders[index].cakes[cakeindex].comment = $scope.orders[index].cakes[cakeindex].comment.substring(0,  $scope.orders[index].cakes[cakeindex].comment.length - 1);
            }
        };
    });