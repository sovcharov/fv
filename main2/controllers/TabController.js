"use strict"
angular.module("InvestorPanel")
.controller("TabController", function () {
	var tab = -1;
	this.setTab = function (value, show){
		if(!show) tab=value;
		 else tab=-1;
	};
	this.isSet = function (value){
		return tab===value;
	};
});