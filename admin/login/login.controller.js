(function () {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['$location', 'UserService', 'AuthenticationService', 'FlashService'];
    function LoginController($location, UserService, AuthenticationService, FlashService) {
        var vm = this;

        vm.login = login;
        vm.user = {};
        vm.user.username = "";
        vm.user.password = "";
        vm.inUser = null;

            (function initController() {
            // reset login status
            //vm.inUser = UserService.GetInUser();
            if(vm.inUser){
                if(vm.inUser.type == 'manager' )
                    $location.path('/manager');
                else if(vm.inUser.type == 'employee' )
                    $location.path('/employee/'+vm.inUser.md5_id);
            }else

            AuthenticationService.ClearCredentials();
        })();

        vm.managerDemo = function(){
            vm.user.company = "shatkonlabs";vm.user.email = "rahul@blueteam.in"; vm.user.password = "rahul";
            login();
        }


        vm.employeeDemo = function(){
            vm.user.company = "shatkonlabs";vm.user.email = "anil@blueteam.in"; vm.user.password = "anil";
            login();
        }

        function login() {
            vm.dataLoading = true;
            AuthenticationService.Login(vm.user, function (resp) {
                console.log("resp",resp);
                if (resp.success) {
                    AuthenticationService.SetCredentials(vm.user.username, vm.user.password);
                    vm.inUser = UserService.GetInUser();
                    if(vm.inUser.type == 'manager' )
                        $location.path('/manager');
                    else
                        $location.path('/employee/'+vm.inUser.md5_id);
                } else {
                    FlashService.Error(resp.message);
                    vm.dataLoading = false;
                }
            });
        };
    }

})();
