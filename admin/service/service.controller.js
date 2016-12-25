(function () {
    'use strict';

    angular
        .module('app')
        .controller('ServiceController', ServiceController);

    ServiceController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function ServiceController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        
        vm.user = null;
        vm.inUser = null;
        vm.data = [];
        initController();
        vm.oldCity = 0;
        function initController() {
            loadUser();
            getAllServices();
        }
        function isEmpty(obj){
            return (Object.getOwnPropertyNames(obj).length === 0);
        }
        vm.logout = function(){
            vm.inUser = null;
            UserService.DeleteInUser();
            $location.path('#/login');
        };

        function loadUser(){
            vm.inUser = UserService.GetInUser();
            if(!vm.inUser.name)
                $location.path('/login');
            console.log("in user",vm.inUser);
        }
        function getAllServices() {
            CandidateService.getAllServices()
                .then(function (response) {
                    vm.allServices = response.allServices;
                    console.log(vm.allServices.name);
                });
        }
    }

})();