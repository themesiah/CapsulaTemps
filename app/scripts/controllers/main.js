'use strict';

/**
 * @ngdoc function
 * @name capsulaTempsApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the capsulaTempsApp
 */
var app = angular.module('capsulaTempsApp');


app.controller('MainCtrl', function ($scope) {
    $scope.awesomeThings = [
        'HTML5 Boilerplate',
        'AngularJS',
        'Karma'
    ];
});

app.controller('MainCtrl', function ($scope, $rootScope, ngDialog) {
    $rootScope.jsonData = '{"foo": "bar"}';
    $rootScope.theme = 'ngdialog-theme-default';
    $scope.directivePreCloseCallback = function (value) {
        if (confirm('Close it? MainCtrl.Directive. (Value = ' + value + ')')) {
            return true;
        }
        return false;
    };
    $scope.preCloseCallbackOnScope = function (value) {
        if (confirm('Close it? MainCtrl.OnScope (Value = ' + value + ')')) {
            return true;
        }
        return false;
    };
    $scope.open = function () {
        ngDialog.open({
            template: '../../views/pop.html',
            controller: 'InsideCtrl',
            data: {foo: 'some data'}});
    };
});
app.controller('InsideCtrl', function ($scope, ngDialog) {
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
});
app.controller('SecondModalCtrl', function ($scope, ngDialog) {
    $scope.closeSecond = function () {
        ngDialog.close();
    };
});
 