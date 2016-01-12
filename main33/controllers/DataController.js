(function () {
    "use strict";
    angular.module("InvestorPanel")
        .controller("DataController", function ($scope, $http) {
            var getStoresData;
            $scope.bakeries = [];
            getStoresData = function (data) {
                var i, getData;
                getData = function (storeID) {
//                    var dataToSend = {};
//                    dataToSend.id = storeID;
                    $http({
                        method: 'POST',
                        url: 'data/getStoreData.php',
                        data: storeID,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    })
                        .success(function (dataReceived) {
                            var indexLocal = $scope.bakeries.length;
                            $scope.bakeries[indexLocal] = {};
                            $scope.bakeries[indexLocal].number = parseInt(dataReceived, 10);
                        });
                };
                for (i = 0; i < data.length; i = i + 1) {
                    getData(data[i]);
                }
            };
            (function () {
                $http.get('data/getUserStores.php').success(function (data) {
                    $scope.stores = data;
                    getStoresData(data);
                });
            }());
        });
}());