(function () {
    'use strict';

    angular
        .module('app')
        .controller('AddServiceRequestController', AddServiceRequestController);
    AddServiceRequestController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function AddServiceRequestController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        
        vm.user = null;
        vm.inUser = null;
        vm.example1model = []; 
        vm.selected = [];
        vm.data = [];
        vm.takeStartTime = openCalender;
        initController();
        vm.oldCity = 0;
        function initController() {
            loadUser();
            getAllServices();
            openCalender();
        }
        function isEmpty(obj){
            return (Object.getOwnPropertyNames(obj).length === 0);
        }
        function loadUser(){
            vm.inUser = UserService.GetInUser();
            console.log("in user",vm.inUser);
        }
        
        function getAllServices() {
            CandidateService.getAllServices()
                .then(function (response) {
                    vm.allServices = response.allServices;
                    console.log(vm.allServices.name);
                });
        }
        
        function openCalender(){
            $('.takeStartTime').datetimepicker({
                
                weekStart: 1,
                autoclose: 1,
                format : 'yyyy-mm-dd hh:ii'
            });
        }
        vm.addServiceRequest = function() {
            
            vm.dataLoading = true;
            var datetime = $('#datetimeValue').val();
            var startTime = datetime.split(" ")[1]+":00";
            var time = startTime.split(":");
            var endtime = ""+parseInt(parseInt(vm.data.totalHour)+parseInt(time[0]))+":"+time[1]+":00";
            console.log(datetime+'/'+startTime+'/'+endtime);
            if(datetime == undefined || datetime == ""){
                alert("please select starting date and time of service");
                vm.dataLoading = false;
            }
            else if(vm.data.service == undefined){
                alert("Select Service");
                vm.dataLoading = false;
            }
            else if(vm.data.totalHour == undefined){
                alert("Please enter total number of hours of service needed");
                vm.dataLoading = false;
            }
            else {

                var request = '{"root": {"name":"'+vm.data.name+'","mobile":"'+vm.data.mobile+'","requirements":"'
                            +vm.data.service+'","service_id":"'+vm.data.service+'","user_id": "27","user_type":"customer",'+'"start_datatime":"'
                            +datetime+'","service_type": "direct-service",'+'"remarks": "'+vm.data.remarks
                            +' by blueteam admin page","start_time":"'+startTime+'",'+'"end_time":"'+endtime
                            +'","location":"","address":"'+vm.data.address+'","priority": "3",'
                            +'"service_provider_id":"0"}}';
                console.log(request);
                CandidateService.CreateServiceRequest(request)
                    .then(function (response) {
                        if (response.root.sr_id) {
                            FlashService.Success('Added successful', true);
                            $location.path('#/serviceRequests');
                            
                        } else {
                            FlashService.Error(response.message);
                            vm.dataLoading = false;
                        }
                    });
            }
            
        }
    }

})();