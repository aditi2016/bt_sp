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

        initController();

        function initController() {
            loadUser();
            getAllServiceRequests();
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

        function getAllServiceRequests(){
            vm.dataLoading = true;

            CandidateService.getAllServiceRequests()
                .then(function (response) {
                    vm.serviceRequests = response.services;

                    vm.dataLoading = false;

                    console.log(vm.serviceRequests[1].name);
                });

        }

        /*function loadCurrentUser() {
            UserService.GetByUsername($rootScope.globals.currentUser.username)
                .then(function (user) {
                    vm.user = user;
                });
        }*/

        function loadAllUsers() {
            UserService.GetAll()
                .then(function (users) {
                    vm.allUsers = users;
                });
        }

        function deleteUser(id) {
            UserService.Delete(id)
            .then(function () {
                loadAllUsers();
            });
        }





    }

})();