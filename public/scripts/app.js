'use strict';


angular.module('cloudcomputing2017App', [
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ui.router',
    'thatisuday.dropzone',
    'restangular',
    'satellizer',
    'LocalStorageModule',
    '19degrees.ngSweetAlert2'
  ])
  .config(function(RestangularProvider) {
    RestangularProvider.setBaseUrl('http://localhost:9000/api/v1');
  })
  .config(function(localStorageServiceProvider) {
    localStorageServiceProvider
      .setPrefix('cloudComputing')
      .setStorageType('localStorage')
      .setNotify(true, true)
  })
  .config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    //delete $httpProvider.defaults.headers.common['X-Requested-With'];
    $urlRouterProvider.otherwise('/');
    $stateProvider
      .state('main', {
        url: '/main',
        auth: true,
        templateUrl: 'views/main.html',
        controller: 'MainCtrl'
      })
      .state('main.dashboard', {
        url: '/dashboard',
        auth: true,
        templateUrl: 'views/dashboard.html',
        controller: 'DashboardCtrl'
      })
      .state('main.dashboard.module', {
        url: '/module/:id',
        auth: true,
        templateUrl: 'views/module.html',
        controller: 'ModuleCtrl'
      })
      .state('main.account', {
        url: '/account',
        auth: true,
        templateUrl: 'views/account.html',
        controller: 'AccountCtrl'
      })
      .state('login', {
        url: '/',
        templateUrl: 'views/login.html',
        controller: 'LoginCtrl',
      })
  })

  .config(function($authProvider) {

    $authProvider.httpInterceptor = function() {
        return true;
      },
      $authProvider.withCredentials = false;
    $authProvider.tokenRoot = null;
    $authProvider.baseUrl = 'http://localhost:9000/api/v1';
    $authProvider.loginUrl = '/auth/login';
    $authProvider.signupUrl = '/auth/signup';
    $authProvider.unlinkUrl = '/auth/unlink/';
    $authProvider.tokenName = 'token';
    $authProvider.tokenPrefix = 'satellizer';
    $authProvider.tokenHeader = 'Authorization';
    $authProvider.tokenType = 'Bearer';
    $authProvider.storageType = 'localStorage';

  });
