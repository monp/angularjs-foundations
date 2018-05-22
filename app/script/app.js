'use strict';

angular.module('comicsApp', ['ngRoute', 'ngResource'])
    .constant('baseURL', 'http://localhost:8080')
    .config(['$routeProvider', function($routeProvider) {
        $routeProvider
            .when('/about', {
                templateUrl : 'view/about.html'
            })
            .when('/home', {
                templateUrl : 'view/home.html'
            })
            .when('/comics', {
                templateUrl : 'comics.html',
                controller  : 'comicsController'
            })
            .when('/comic/:id', {
                templateUrl : 'view/comic.html'
            })
            .otherwise('/home');
    }])
;