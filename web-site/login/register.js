(function () {
    'use strict';

    angular
        .module('app')
        .controller('RegisterController', RegisterController);

    RegisterController.$inject = ['$location', 'AuthenticationService', 'FlashService'];
    function RegisterController($location, AuthenticationService, FlashService) {
        var vm = this;

        vm.Register = Register;

     /*   (function initController() {
            // reset login status
            AuthenticationService.ClearCredentials();
        })();*/

        function Register() {
            vm.dataLoading = true;
            AuthenticationService.Register(vm.user, function (resp) {
                console.log("resp",resp);
                if (resp.success) {
                    AuthenticationService.SetCredentials(vm.user.name, vm.user.password , vm.user.organization , vm.user.description , vm.user.mobile , vm.user.email , vm.user.city_id , vm.user.area_id) , vm.user.address;
                    $location.path('/home');
                } else {
                    FlashService.Error(resp.message);
                    vm.dataLoading = false;
                }
            });
        };
    }

})();
