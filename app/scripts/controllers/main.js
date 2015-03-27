'use strict';

/**
 * @ngdoc function
 * @name capsulaTempsApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the capsulaTempsApp
 */
angular.module('capsulaTempsApp')
        .controller('MainCtrl', function ($scope) {
            $scope.awesomeThings = [
                'HTML5 Boilerplate',
                'AngularJS',
                'Karma'
            ];
        })
        .controller('MainCtrl', function ($scope, $rootScope, ngDialog) {

            $scope.open = function () {
                ngDialog.open({
                    template: '../../views/pop.html',
                    controller: 'InsideCtrl',
                    data: {foo: 'some data'}});
            };
        })
        .controller('InsideCtrl', function ($scope, ngDialog) {
            $scope.dialogModel = {
                message: 'message from passed scope'
            };
            $scope.openSecond = function () {
                ngDialog.open({
                    template: '<h3><a href="" ng-click="closeSecond()">Close all by click here!</a></h3>',
                    plain: true,
                    closeByEscape: false,
                    controller: 'SecondModalCtrl'
                });
            };
        })
        .controller('SecondModalCtrl', function ($scope, ngDialog) {
            $scope.closeSecond = function () {
                ngDialog.close();
            };
        });
