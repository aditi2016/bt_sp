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
        vm.upRequest = upRequest;
        vm.threeMonths = [];
        vm.whichMonth = {};
        vm.currentMonthIndex = 0;
        vm.setCurrentMon = function(monthname){
            console.log("i am in setCurrentMonth",monthname);
            vm.whichMonth.name = monthname;
            vm.whichMonth.num = vm.threeMonths[vm.currentMonthIndex].num;
            getAllServiceRequests(vm.status, monthname);
        }

        function loadMonths(){
            var months = new Array(12);
            months[0] = "January";
            months[1] = "February";
            months[2] = "March";
            months[3] = "April";
            months[4] = "May";
            months[5] = "June";
            months[6] = "July";
            months[7] = "August";
            months[8] = "September";
            months[9] = "October";
            months[10] = "November";
            months[11] = "December";

            var myDate = new Date();
            vm.whichMonth.name = months[myDate.getMonth()].toString()+", "+myDate.getFullYear().toString();
            vm.whichMonth.num = myDate.getMonth();
            vm.threeMonths[0] = {"name":months[myDate.getMonth()].toString()+", "+myDate.getFullYear().toString(),"num":myDate.getMonth()};
            if((myDate.getMonth()-1) < 0){
                vm.threeMonths[1] = {"name":"December, "+(myDate.getFullYear()-1).toString(), "num":11};
                vm.threeMonths[2] = {"name":"November, "+(myDate.getFullYear()-1).toString(), "num":10};
            }
            else {
                if((myDate.getMonth()-2) <0){
                    vm.threeMonths[1] = {"name":months[myDate.getMonth()-1].toString()+", "+myDate.getFullYear().toString(), "num":myDate.getMonth()-1};
                    vm.threeMonths[2] = {"name":"December, "+(myDate.getFullYear()-1).toString(), "num":11};
                }
                else {
                    vm.threeMonths[1] = {"name":months[myDate.getMonth()-1].toString()+", "+myDate.getFullYear().toString(), "num":myDate.getMonth()-1};
                    vm.threeMonths[2] = {"name":months[myDate.getMonth()-2].toString()+", "+myDate.getFullYear().toString(),"num":myDate.getMonth()-2};
                }                
            }        
            
            console.log(vm.threeMonths);
        }
        initController();

        function initController() {
            loadUser();
            loadMonths();
            getAllServiceRequests('open', vm.whichMonth.name);
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
                        $("#updateRequest").modal("hide");
                        getAllServiceRequests(vm.status);
                    });
            }
        }
        vm.updateSR = function (){
            vm.dataLoading = true;
            if(vm.data.serviceRequestId == undefined){
                alert("Error occured. Please Try Again");
            }
            else if(vm.data.paddress == undefined){
                alert("Please enter address");
            }
            else if(vm.data.premarks == undefined){
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
        function getAllServiceRequests(type, month){
            vm.dataLoading = true;
            vm.status = type ;
            if(month == undefined) month = vm.whichMonth.name;
            CandidateService.getAllServiceRequests(type, month)
                .then(function (response) {
                    vm.serviceRequests = response.root.srs;
                    vm.dataLoading = false;
                    console.log(vm.serviceRequests[0].name);
                });

        }

    }

})();