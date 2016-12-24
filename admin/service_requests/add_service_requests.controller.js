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
        vm.data.datetimeValue = new Date();
        vm.data.datetimeValue.setHours(7);
        vm.data.datetimeValue.setMinutes(0);
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
        vm.data.startTimeSet = false;
        function openCalender(){
            $('.takeStartTime').datetimepicker({
                
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1
            });
        }
        vm.addServiceProvider = function() {
                       
            console.log("addServiceProvider function",vm.selected);
            vm.dataLoading = true;
            if(vm.registered == false){
                var services = vm.data.serviceName ;
                var city = vm.data.cityId;
                var area = vm.data.areaId;
                var provider = '{"address" : "'+vm.data.address+'","area_id" : "'+vm.data.areaId+
                            '","city_id" : "'+vm.data.cityId+'","mobile" : "'+vm.data.mobile+
                            '","name" : "'+vm.data.name+'","description" : "'+vm.data.description+
                            '","organization" : "'+vm.data.organization+'","email" : "'+vm.data.email+'"}';
            }
            else {
                var services = vm.selected.toString() ;
                var city = vm.data.city_id;
                var area = vm.data.area_id;
                var provider = '{"address" : "'+vm.data.address+'","area_id" : "'+vm.data.area_id+
                            '","city_id" : "'+vm.data.city_id+'","mobile" : "'+vm.data.mobile+
                            '","name" : "'+vm.data.name+'","description" : "'+vm.data.description+
                            '","organization" : "'+vm.data.organization+'","email" : "'+vm.data.email+'"}';
            }
            if(services.length == 0){
                alert("please select at least 1 service");
                vm.dataLoading = false;
            }
            else if(city == undefined){
                alert("Select City");
                vm.dataLoading = false;
            }
            else if(area == undefined){
                alert("Select Area");
                vm.dataLoading = false;
            }
            else {
                CandidateService.CreateServiceProvider(provider)
                    .then(function (response) {
                        if (response.service_providers.id) {
                            FlashService.Success('Added successful', true);
                            $location.path('/serviceprovider/'+response.service_providers.id+'/service/'+services);
                            
                        } else {
                            FlashService.Error(response.message);
                            vm.dataLoading = false;
                        }
                    });
            }
            
        }
    }

})();