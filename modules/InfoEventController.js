(function () {
    "use strict";
    angular.module("InvestorPanel").controller("InfoEventController", function ($rootScope, $interval) {
        $rootScope.infoEvents = [];
        var stop,
            updateInfoEvents = function () {
                var i = 0;
                while ($rootScope.infoEvents[i]) {
                    if ($rootScope.infoEvents[i].life > 0) {
                        $rootScope.infoEvents[i].life -= 1;
                        i += 1;
                    } else {
                        $rootScope.infoEvents.splice(i, 1);
                    }
                }
                if (!$rootScope.infoEvents.length) {
                    $interval.cancel(stop);
                }
            };
        $rootScope.addInfoEvent = function (eventClass, text, comment) {
            var event = {
                eventClass: eventClass,
                text: text,
                life: 5
            },
                index = $rootScope.infoEvents.length;
            if (comment) {
                event.comment = comment;
            } else {
                event.comment = "---------";
            }
            $rootScope.infoEvents[index] = event;
            if (index === 0) {
                stop = $interval(updateInfoEvents, 500);
            }
        };
        $rootScope.checkInfoEventClass = function (event) {
            return event.eventClass ? true : false;
        };
    });
}());
