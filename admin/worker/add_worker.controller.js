(function () {
    'use strict';

    angular
        .module('app')
        .controller('AddWorkerController', AddWorkerController);
    AddWorkerController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function AddWorkerController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        vm.id = $routeParams.id;
        vm.user = null;
        vm.inUser = null;
        vm.data = [];
        initController();
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
        
        vm.uploadIcon = function(file_id){
            if(document.getElementById(file_id).files[0]){
                CandidateService.uploadImg(file_id).then(function (response) {
                    if (response.file.id) {
                        vm.filesUpStatus[file_id] = true;
                        vm.data["worker_"+file_id] = response.file.id;
                        return false;
                    } else {
                        console.error(response);
                        return false;
                    }
                });
            }
            else { vm.filesUpStatus[file_id] = true; }
        }
        vm.filesUpStatus = new Array();
        vm.filesUpStatus["vc"] = false;
        vm.filesUpStatus["ac"] = false;
        vm.filesUpStatus["pc"] = false;
        vm.filesUpStatus["photo"] = false;
        vm.filesUpStatus["dl"] = false;
        vm.filesUpStatus["pv"] = false;


        vm.flag = true;
        vm.registerWorker = function registerWorker() {

            if(vm.flag) {
                vm.uploadIcon('vc');
                vm.uploadIcon('ac');
                vm.uploadIcon('pc');
                vm.uploadIcon('photo');
                vm.uploadIcon('dl');
                vm.uploadIcon('pv');
            }
            //while (true){

                if(
                    vm.filesUpStatus["vc"] &&
                    vm.filesUpStatus["ac"] &&
                    vm.filesUpStatus["pc"] &&
                    vm.filesUpStatus["photo"] &&
                    vm.filesUpStatus["dl"] &&
                    vm.filesUpStatus["pv"]

                ) vm.flag = true;
                else {

                    vm.flag = false;
                    $timeout(function () {
                        vm.registerWorker();
                    }, 3000);
                    return;
                }
            //}

            vm.dataLoading = true;
            vm.data.worker_localId = 1;
            vm.data.customer_id = vm.id ;
            console.log("registerWorker function",vm.data);
            CandidateService.addWorker(vm.worker, vm.inUser.society_id)
                .then(function (response) {
                    console.log("safa",response);
                    if (response.root.worker_id) {
                        FlashService.Success('Registration successful', true);
                        vm.dataLoading = false;
                        vm.user = null;
                        //loadToCallCandidates();
                        //$location.path('/login');
                    } else {
                        FlashService.Error("Failed to insert");
                        vm.dataLoading = false;
                    }
                });

        }
    }

})();