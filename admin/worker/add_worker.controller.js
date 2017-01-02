(function () {
    'use strict';

    angular
        .module('app')
        .controller('AddWorkerController', AddWorkerController);
    AddWorkerController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function AddWorkerController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        vm.id = $routeParams.id;
        if(vm.id == 27 || vm.id == 0 || vm.id == '' || vm.id == null){
            alert("Can not add worker");
            $location.path('/serviceRequests');
        }
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
                        vm.data[file_id] = response.file.id;
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
            vm.dataLoading = true;
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
            var vm.data.worker_vc = vm.data.worker_vc || 0 ;
            var vm.data.worker_ac = vm.data.worker_ac || 0 ;
            var vm.data.worker_pc = vm.data.worker_pc || 0 ;
            var vm.data.worker_photo = vm.data.worker_photo || 0 ;
            var vm.data.worker_dl = vm.data.worker_dl || 0 ;
            var vm.data.worker_pv = vm.data.worker_pv || 0 ;
            var data = '{"root":{"worker_name":"'+vm.data.name+'","worker_mobile":"'+vm.data.mobile+
            '","worker_address":"'+vm.data.worker_address+'","worker_photo":"'+vm.data.photo+
            '","customer_id":"'+vm.id+'","worker_localId":"'+1+'","worker_service":"'+vm.data.service+
            '","worker_pv":"'+vm.data.pv+'","worker_ac":"'+vm.data.ac+'","worker_vc":"'+vm.data.vc+
            '","worker_dl":"'+vm.data.dl+'","worker_pc":"'+vm.data.pc+'","worker_emergency_no":"'+
            vm.data.emergency_no+'","worker_native_add":"'+vm.data.native_add+'"}}'
            console.log("registerWorker function",data);
            CandidateService.addWorker(vm.data, 1)
                .then(function (response) {
                    if (response.root.worker_id) {
                        FlashService.Success('Registration successful', true);
                        vm.dataLoading = false;
                        $location.path('/serviceRequests');
                    } else {
                        FlashService.Error("Failed to insert");
                        vm.dataLoading = false;
                    }
                });

        }
    }
$wId = createCustomerWorker($db_handle, $input->root->worker_name, $input->root->worker_mobile, 
        $input->root->worker_address, $input->root->worker_photo, $input->root->customer_id, 
        $input->root->worker_localId, $input->root->worker_service, $input->root->worker_pv, 
        $input->root->worker_ac, $input->root->worker_vc, $input->root->worker_dl, 
        $input->root->worker_pc,
        $input->root->worker_emergency_no, $input->root->worker_native_add, $route[2]);
})();