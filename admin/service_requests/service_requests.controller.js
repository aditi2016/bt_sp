(function () {
    'use strict';

    angular
        .module('app')
        .controller('ServiceRequestController', ServiceRequestController);

    ServiceRequestController.$inject = ['UserService',  'CandidateService', '$rootScope', 'FlashService','$location'];
    function ServiceRequestController(UserService, CandidateService,  $rootScope, FlashService,$location) {
        var vm = this;

        vm.user = null;
        vm.inUser = null;
        vm.allUsers = [];
        
        vm.dataLoading = false;
        vm.getAllServiceRequests = getAllServiceRequests;
        vm.allStatus = [];
        initController();

        function initController() {
            loadUser();
            getAllServiceRequests('open');
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

        function getAllServiceRequests(type){
            vm.dataLoading = true;

            CandidateService.getAllServiceRequests(type)
                .then(function (response) {
                    vm.serviceRequests = response.root.srs;

                    vm.dataLoading = false;

                    console.log(vm.serviceRequests[1].name);
                });

        }

    }

})();