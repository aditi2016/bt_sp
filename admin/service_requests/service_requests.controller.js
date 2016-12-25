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
        vm.data = [];
        vm.dataLoading = false;
        vm.getAllServiceRequests = getAllServiceRequests;
        vm.allStatus = [];
        vm.status = 'open'
        vm.chStatus = chStatus;
        vm.changeStatus = changeStatus;
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
        function chStatus(id) {
            vm.data.serviceRequestId = id;
            vm.data.changeStatus = vm.status;
            $("#statusUpdate").modal("show");
        }
        function upRequest(id, address, remarks) {
            vm.data.serviceRequestId = id;
            vm.data.paddress = address;
            vm.data.premarks = remarks;
            $("#updateRequest").modal("show");
        }
        function changeStatus() {
            if(vm.data.serviceRequestId == undefined){
                alert("Error occured. Please Try Again");
            }
            else if(vm.data.changeStatus == undefined){
                alert("Please select status");
            }
            else {
                var data = '{"root": { "sr_id": "'+vm.data.serviceRequestId+
                            '", "user_id": "3", "key": "status", "value": "'+
                            vm.data.changeStatus+'" }}';
                CandidateService.changeStatus(data)
                    .then(function (response) {
                        $("#statusUpdate").modal("hide");
                        getAllServiceRequests(vm.status);
                    });
            }
        }
        vm.updateSR = function (){
            vm.dataLoading = true;
            if(vm.data.serviceRequestId == undefined){
                alert("Error occured. Please Try Again");
            }
            else if(vm.data.address == undefined){
                alert("Please enter address");
            }
            else if(vm.data.remarks == undefined){
                alert("Please enter remarks");
            }
            else {
                var data = '{"root": { "sr_id": "'+vm.data.serviceRequestId+
                            '", "user_id": "3", "key": "address", "value": "'+
                            vm.data.address+'", "remark": "'+ vm.data.remarks+'" }}';
                CandidateService.changeStatus(data)
                    .then(function (response) {
                        $("#statusUpdate").modal("hide");
                        getAllServiceRequests(vm.status);
                    });
            }
        }
        function getAllServiceRequests(type){
            vm.dataLoading = true;
            vm.status = type ;
            CandidateService.getAllServiceRequests(type)
                .then(function (response) {
                    vm.serviceRequests = response.root.srs;
                    vm.dataLoading = false;
                    console.log(vm.serviceRequests[1].name);
                });

        }

    }

})();