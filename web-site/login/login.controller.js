(function () {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['$location', 'AuthenticationService', 'CandidateService', 'FlashService'];
    function LoginController($location, AuthenticationService, CandidateService,FlashService) {
        var vm = this;

        vm.login = login;
        vm.registerVendor =  registerVendor;

        (function initController() {
            // reset login status
            AuthenticationService.ClearCredentials();
        })();

        function registerVendor() {
            console.log("registerWorker function",vm.vendor);

            vm.dataLoading = true;
            
            CandidateService.Create(vm.vendor)
                .then(function (response) {
                    console.log("safa",response);
                    if (response.candidate) {
                        FlashService.Success('Registration successful', true);
                        vm.dataLoading = false;
                        vm.user = null;
                        loadToCallCandidates();
                        //$location.path('/login');
                    } else {
                        FlashService.Error(response.error.text);
                        vm.dataLoading = false;
                    }
                });
            
            
        }

        function login() {
            vm.dataLoading = true;
            AuthenticationService.Login(vm.user, function (resp) {
                console.log("resp",resp);
                if (resp.success) {
                    AuthenticationService.SetCredentials(vm.user.username, vm.user.password);
                    $location.path('/home');
                } else {
                    FlashService.Error(resp.message);
                    vm.dataLoading = false;
                }
            });
        };
    }

})();
