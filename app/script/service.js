'use strict';

angular.module('comicsApp')
    .service('comicsService', ['$resource', 'baseURL', function($resource, baseURL) {
      this.getComics = function() {
        return $resource(baseURL + '/v1/public/comics', { 'limit': 50 });
      };
      this.getComic = function(id) {
        return $resource(baseURL + '/v1/public/comics/:id', { 'id': id });
      };
    }])
;