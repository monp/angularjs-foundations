'use strict';

angular.module('comicsApp', ['ngRoute', 'ngResource'])
    .constant('baseURL', 'http://localhost:8080')
    .config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {
        $routeProvider
            .when('/about', {
                templateUrl : 'view/about.html'
            })
            .when('/home', {
                templateUrl : 'view/home.html',
                controller  : 'tokenController'
            })
            .when('/comics', {
                templateUrl : 'comics.html',
                controller  : 'comicsController'
            })
            .when('/comic/:id', {
                templateUrl : 'view/comic.html'
            })
            .otherwise('/home');
        // Intercept all HTTP requests and send the stored JWT Token if any
        $httpProvider
            .interceptors
            .push(['$q', '$location', function ($q, $location) {
                return {
                    'request': function (config) {
                        config.headers = config.headers || {};
                        // Attach token to HTTP headers if present in localStorage
                        if (localStorage.token) {
                            config.headers.Authorization = 'Bearer ' + localStorage.token;
                        }
                        return config;
                    },
                    'responseError': function (response) {
                        // Redirect to '/' if API respond with 401 HTTP Code
                        if (response.status === 401) {
                            console.error("Unauthorized request, provide a valid JWT token.");
                            $location.path('/');
                        }
                        return $q.reject(response);
                    }
                };
            }]);
    }])
;