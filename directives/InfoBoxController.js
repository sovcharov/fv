(function () {
    "use strict";
    angular.module("InvestorPanel").controller("InfoBoxController", function ($rootScope, $interval) {

        $rootScope.infoEvents = [];

        var stop, updateInfoEvents, eventClasses;

        eventClasses = {
            danger: 'infoBoxWarning',
            success: 'infoBoxSuccess'
        };

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
            var index, event;
            event = {
                eventClass: eventClasses[eventClass],
                text: text,
                comment: comment,
                life: 5
            };

            if (!comment) {
                event.comment = "---------";
            }

            index = $rootScope.infoEvents.length;

            $rootScope.infoEvents[index] = event;

            if (index === 0) {
                stop = $interval(updateInfoEvents, 500);
            }
        };
    });
}());
