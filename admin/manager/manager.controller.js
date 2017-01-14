(function () {
    'use strict';

    angular
        .module('app')
        .controller('ManagerController', ManagerController);

    ManagerController.$inject = ['UserService',  'CandidateService', '$rootScope', 'FlashService','$location'];
    function ManagerController(UserService, CandidateService,  $rootScope, FlashService,$location) {
        var vm = this;

        vm.user = null;
        vm.inUser = null;
        vm.allUsers = [];
        vm.deleteUser = deleteUser;
        vm.loadUser = loadUser;

        initController();

        function initController() {
          //  loadCurrentUser();
           // loadAllUsers();

            loadUser();
            loadNotInstallApps();
            loadNotUsingApps();
            loadToCallCandidates();
            getInterestedServices();
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

        function loadNotInstallApps(){
            vm.dataLoading = true;
            CandidateService.notInstallApps()
                .then(function (response) {
                    vm.notInstallApps = response.service_providers;
                    vm.dataLoading = false;
                });
        }
        function getSearchResults(){
            vm.dataLoading = true;
            CandidateService.getSearchResults()
                .then(function (response) {
                    vm.searchResults = response.searchs;
                    vm.dataLoading = false;
                });
        }
        function getInterestedServices(){
            vm.dataLoading = true;
            CandidateService.getInterestedServices()
                .then(function (response) {
                    vm.interestedServices = response.interestedServices;
                    vm.dataLoading = false;
                });
        }
        function loadNotUsingApps(){
            vm.dataLoading = true;
            CandidateService.notUsingApps()
                .then(function (response) {
                    vm.notUsingApps = response.service_providers;
                    vm.dataLoading = false;
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