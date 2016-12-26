(function () {
    'use strict';

    angular
        .module('app', ['ngRoute', 'ngCookies', 'datatables','base64','ui.bootstrap.datetimepicker'])
        .config(config)
        .run(run);

    config.$inject = ['$routeProvider', '$locationProvider'];
    function config($routeProvider, $locationProvider) {
        $routeProvider
            .when('/', {
                controller: 'LoginController',
                templateUrl: 'login/login.view.html',
                controllerAs: 'vm'                
            })

            .when('/login', {
                controller: 'LoginController',
                templateUrl: 'login/login.view.html',
                controllerAs: 'vm'
            })

            .when('/register', {
                controller: 'RegisterController',
                templateUrl: 'register/register.view.html',
                controllerAs: 'vm'
            })

            .when('/home', {
                controller: 'HomeController',
                templateUrl: 'home/home.view.html',
                controllerAs: 'vm'                
            })

            .when('/manager', {
                controller: 'ManagerController',
                templateUrl: 'manager/manager.view.html',
                controllerAs: 'vm'
            })

            .when('/service', {
                controller: 'ServiceController',
                templateUrl: 'service/service.view.html',
                controllerAs: 'vm'
            })

            .when('/addService', {
                controller: 'AddServiceController',
                templateUrl: 'service/add_service.view.html',
                controllerAs: 'vm'
            })

            .when('/addService/:id', {
                controller: 'AddServiceController',
                templateUrl: 'service/add_service.view.html',
                controllerAs: 'vm'
            })
            .when('/addWorker/:id', {
                controller: 'AddWorkerController',
                templateUrl: 'worker/add_worker.view.html',
                controllerAs: 'vm'
            })

            .when('/employee/:emp', {
                controller: 'EmployeeController',
                templateUrl: 'employee/employee.view.html',
                controllerAs: 'vm'
            })

            .when('/serviceRequests', {
                controller: 'ServiceRequestController',
                templateUrl: 'service_requests/service_requests.view.html',
                controllerAs: 'vm'
            })

            .when('/addServiceRequests', {
                controller: 'AddServiceRequestController',
                templateUrl: 'service_requests/add_service_requests.view.html',
                controllerAs: 'vm'
            })

            .when('/addServiceProvider/:serviceId/:serviceName/:cityId/:cityName/:areaId/:areaName', {
                controller: 'ServiceProviderController',
                templateUrl: 'service_provider/service_provider.view.html',
                controllerAs: 'vm'
            })

            .when('/addServiceProvider', {
                controller: 'ServiceProviderController',
                templateUrl: 'service_provider/service_provider.view.html',
                controllerAs: 'vm'
            })
            
            .when('/serviceprovider/:id/service', {
                controller: 'ServiceProviderServiceController',
                templateUrl: 'service_provider/service_provider_service.view.html',
                controllerAs: 'vm'
            })

            .when('/home/:mobile', {
                controller: 'HomeController',
                templateUrl: 'home/home.view.html',
                controllerAs: 'vm'
            })

            .otherwise({ redirectTo: '/' });
    }

    run.$inject = ['$rootScope', '$location', '$cookieStore', '$http'];
    function run($rootScope, $location, $cookieStore, $http) {
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }

        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in and trying to access a restricted page
            var restrictedPage = $.inArray($location.path(), ['/login', '/register']) === -1;
            var loggedIn = $rootScope.globals.currentUser;
            if (restrictedPage && !loggedIn) {
                $location.path('/login');
            }
        });
    }

})();