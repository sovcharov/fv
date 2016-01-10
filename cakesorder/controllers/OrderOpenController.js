//This controller posting every 5 seconds to server all orders, that are currently open to edit by current user. 
//Server updates time variable at each order sent and also updates name to current user at each order sent by this ctrl. 
angular.module("FVCakes")
    .controller("OrderOpenController", function ($http, $rootScope, $interval) {
        "use strict";
        $interval(function () {
            var orders = [],
                i,
                data = {};
            if ($rootScope.orders.length) {
                for (i = 0; i < $rootScope.orders.length; i += 1) {
                    if ($rootScope.orders[i].id) {
                        orders[orders.length] = $rootScope.orders[i].id;
                    }
                }
                if (orders.length) {
                    data.user = $rootScope.user.id;
                    data.orders = orders;
                    $http({
                        method: 'POST',
                        url: 'services/ordersOpen.php',
                        data: data,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    });
                }
            }
        }, 5000);
    });