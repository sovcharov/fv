(function () {
    "use strict";
    angular.module("InvestorPanel").controller("InfoBoxController", function ($rootScope, $interval, $scope) {

        $rootScope.infoEvents = [];

        var stop, updateInfoEvents, eventClasses;

        eventClasses = {
            danger: 'infoBoxDanger',
            success: 'infoBoxSuccess'
        };

        updateInfoEvents = function () {
            var i = 0;
            while ($rootScope.infoEvents[i]) {
                if ($rootScope.infoEvents[i].life > 0) {
                    $rootScope.infoEvents[i].life -= 1;
                    i += 1;
                } else {
                    if (!$rootScope.infoEvents[i].waitForClick) {
                        $rootScope.infoEvents.splice(i, 1);
                    } else {
                        i += 1;
                    }
                }

            }
            if (!$rootScope.infoEvents.length) {
                $interval.cancel(stop);
            }
        };

        $scope.deleteEvent = function (i) {
            $rootScope.infoEvents.splice(i, 1);
        };

        $rootScope.addInfoEvent = function (eventClass, text, comment, waitForClick) {
            var index, event;
            event = {
                eventClass: eventClasses[eventClass],
                text: text,
                comment: comment || '---------',
                life: 5,
                waitForClick: waitForClick
            };

            index = $rootScope.infoEvents.length;

            $rootScope.infoEvents[index] = event;

            if (index === 0) {
                stop = $interval(updateInfoEvents, 500);
            }
        };
    });
}());
