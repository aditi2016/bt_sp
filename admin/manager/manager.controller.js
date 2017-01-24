(function () {
    'use strict';

    angular
        .module('app')
        .controller('ManagerController', ManagerController);

    ManagerController.$inject = ['UserService',  'CandidateService', '$rootScope', 'FlashService','$location','$interval'];
    function ManagerController(UserService, CandidateService,  $rootScope, FlashService,$location,$interval) {
        var vm = this;

        vm.user = null;
        vm.inUser = null;
        vm.allUsers = [];
        vm.deleteUser = deleteUser;
        vm.loadUser = loadUser;
        vm.stopAudio = stopAudio;
        var audio = new Audio('./tune.mp3');
        initController();
        $interval(getRecentCall, 120000);
        function initController() {
            loadUser();
            loadNotInstallApps();
            loadNotUsingApps();
            getSearchResults();
            loadAllSPNotFound();
            getInterestedServices();
            getRecentCall();
        }

        vm.logout = function(){
            vm.inUser = null;
            UserService.DeleteInUser();
            $location.path('#/login');
        };

        function loadAllSPNotFound(){
            vm.dataLoading = true;
            CandidateService.GetAll()
                .then(function (response) {
                    vm.toFindServices = response.services;
                    vm.dataLoading = false;
                });
        }

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
        function getRecentCall() {
            CandidateService.getRecentCall()
                .then(function (response) {
                    if(response.mobiles[0].id){
                        vm.recentCall = response.mobiles;
                        $("#recentCallModal").modal("show");
                        playAudio();
                    }
                    else{
                        console.log('error');
                    }
                });
        }
        function playAudio() {
            audio.loop  = true;
            audio.play();
        };
        function stopAudio() {
            console.log("hi");
            audio.pause();
            audio.currentTime = 0;
        }
    }

})();