(function(){
"use strict"
var FVIPApp = angular.module('InvestorPanel',[]);
FVIPApp.controller("MainController", function ($scope,$http) {
	$scope.zero=[];
	$scope.getAds = function (venue,id) {
		if(!$scope.zero[id]){
			mySpin.start();
			var urlToSend="https://api.foursquare.com/v2/venues/" + String(venue) + "/tips?limit=5&sort=recent&oauth_token=GLCBZ25SPGCA50EBL4HJQFXQS4DXF0YZRZQMRBF1DKTMIN1Q&v=20150102";
			$http.get(urlToSend).success(function(data) {
				mySpin.stop();
				$scope.zero[id]=[];
				for(var i=0;i<data.response.tips.items.length;i++)
				{						
					$scope.zero[id][i]={};
					$scope.zero[id][i].text = data.response.tips.items[i].text;
					$scope.zero[id][i].name = data.response.tips.items[i].user.firstName;
					var date = new Date(data.response.tips.items[i].createdAt * 1000);
					var month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
					$scope.zero[id][i].date = date.getDate() + ' ' + month[date.getMonth()] + ' ' + date.getFullYear();
				};
				if(!$scope.zero[id][0]){
					$scope.zero[id][0]={};
					$scope.zero[id][0].text = "По этой булочной отзывы не оставлены!"
				};
			});			
		};
	};
});
})();