(function () {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['$location', 'AuthenticationService', 'FlashService'];
    function LoginController($location, AuthenticationService, FlashService) {
        var vm = this;

        vm.login = login;

        (function initController() {
            // reset login status
            AuthenticationService.ClearCredentials();
        })();

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
