(function () {
    "use strict";
    angular.module("InvestorPanel").controller("DataController", function ($scope, $http) {
        var getStoresData;
        $scope.date = new Date();
        $scope.bakeries = [];
        
        getStoresData = function () {
            var i, getData, update = false;
            if ($scope.bakeries.length) {
                update = true;
            }
            getData = function (store) {
                var data = {};
                data.date = $scope.date.getFullYear() + '-' + ($scope.date.getMonth() + 1) + '-' + $scope.date.getDate();
                console.log(data.date);
                data.store = store;
                $http({
                    method: 'POST',
                    url: 'data/getStoreData.php',
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                })
                    .success(function (dataReceived) {
                        if (update) {
                            update = true;
                        } else {
                            var indexLocal = $scope.bakeries.length;
                            $scope.bakeries[indexLocal] = {};
                            $scope.bakeries[indexLocal].number = dataReceived;
                        }
                    });
            };
            
            for (i = 0; i < $scope.stores.length; i = i + 1) {
                getData($scope.stores[i]);
            }
        };
        
        (function () {
            $http.get('data/getUserStores.php').success(function (data) {
                $scope.stores = data;
                getStoresData();
            });
        }());

        $scope.dateChanged = function (date) {
            $scope.date = date;
            getStoresData();
        };
    });
}());