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
            $("#bookNow").modal("show");
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
                        $("#bookNow").modal("hide");
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