(function() {

  "use strict";

  var App = angular.module("App", [
    "App.controllers",
    "App.services",
    "ngRoute",
    "ngResource"
  ]);

  App.config(function ($routeProvider) {
    $routeProvider
      .when('/home', {
           templateUrl: 'view/homepage.html',
           controller: 'homepage'
      })
      .when('/view2', {
           templateUrl: 'view/view2.html'
      })
      .otherwise({redirectTo : 'home'});
  });

}());