(function () {
    "use strict";
    angular.module('ui.bootstrap')
        .controller('DateController', function ($scope, $rootScope) {
            $scope.today = function () {
                $scope.dt = $rootScope.commonDate;//new Date();
            };
            $scope.today();

            $scope.clear = function () {
                $scope.dt = null;
            };

            // Disable weekend selection
    //         $scope.disabled = function (date, mode) {
    // //        return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
    //         };

            $scope.toggleMin = function () {
                //$scope.minDate = $scope.minDate ? null : new Date
                var date = new Date();
                date.setDate(date.getDate() - 30);
                $scope.minDate = $scope.minDate ? null : date;
            };

            $scope.toggleMin();
            $scope.maxDate = new Date();

            $scope.open1 = function () {
                $scope.maxDate = new Date();
                $scope.popup1.opened = true;
            };

            $scope.open2 = function () {
                $scope.popup2.opened = true;
            };

            $scope.setDate = function (year, month, day) {
                $scope.dt = new Date(year, month, day);
            };

            $scope.dateOptions = {
                formatYear: 'yy',
                startingDay: 1
            };

            $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yy', 'shortDate'];
            $scope.format = $scope.formats[2];
            $scope.altInputFormats = ['M!/d!/yyyy'];

            $scope.popup1 = {
                opened: false
            };

            $scope.popup2 = {
                opened: false
            };

            var tomorrow = new Date(), afterTomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            afterTomorrow.setDate(tomorrow.getDate() + 1);
            $scope.events =
                [
                    {
                        date: tomorrow,
                        status: 'full'
                    },
                    {
                        date: afterTomorrow,
                        status: 'partially'
                    }
                ];

            $scope.getDayClass = function (date, mode) {
                if (mode === 'day') {
                    var dayToCheck, i, currentDay;
                    dayToCheck = new Date(date).setHours(0, 0, 0, 0);
                    for (i = 0; i < $scope.events.length; i = i + 1) {
                        currentDay = new Date($scope.events[i].date).setHours(0, 0, 0, 0);
                        if (dayToCheck === currentDay) {
                            return $scope.events[i].status;
                        }
                    }
                }
                return '';
            };

            // $scope.datePlusMinus = function (x) {
            //     var year, month, day, date;
            //     date = $scope.dt;
            //     year = date.getFullYear();
            //     month = date.getMonth();
            //     day = date.getDate() + x;
            //     $scope.setDate(year, month, day);
            // };


        });
}());
