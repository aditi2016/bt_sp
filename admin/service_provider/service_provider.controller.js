(function () {
    'use strict';

    angular
        .module('app')
        .controller('ServiceProviderController', ServiceProviderController);

    ServiceProviderController.$inject = ['UserService',  'CandidateService', '$routeParams', 'FlashService'];
    function ServiceProviderController(UserService, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        console.log($routeParams);
        if(!isEmpty($routeParams)){ 
            vm.registered = false;
            vm.data = [];
            vm.data.serviceName = $routeParams.serviceName;
            vm.data.serviceId = $routeParams.serviceId;
            vm.data.cityId = $routeParams.cityId;
            vm.data.cityName = $routeParams.cityName;
            vm.data.areaId = $routeParams.areaId;
            vm.data.areaName = $routeParams.areaName;
        }
        else {vm.registered = true;}
        vm.user = null;
        vm.inUser = null;
        
        initController();
        vm.oldCity = 0;
        function initController() {
            loadUser();
            getCities();
        }
        function isEmpty(obj){
            return (Object.getOwnPropertyNames(obj).length === 0);
        }
        function loadUser(){
            vm.inUser = UserService.GetInUser();
            console.log("in user",vm.inUser);
        }
        function getCities() {
            CandidateService.getCities()
                .then(function (response) {
                    vm.cities = response.cities;
                    console.log(vm.cities.name);
                });
        }
        function getServices() {
            CandidateService.getServices()
                .then(function (response) {
                    vm.services = response.services;
                    console.log(vm.services.name);
                });
        }
        vm.getAreas = function(){
            console.log('areas');
            if(vm.data.city_id == vm.oldCity){
                return false;
            }
            else {
                CandidateService.getCityAreas(vm.data.city_id)
                    .then(function (response) {
                        vm.areas = response.areas;
                        console.log(vm.areas.name);
                    });
                vm.oldCity = vm.data.city_id;
            }
        }
        vm.addServiceProvider = function() {
            console.log("addServiceProvider function",vm.data);
            vm.dataLoading = true;
            if(vm.registered == false){
                var provider = '{"address" : "'+vm.data.address+'","area_id" : "'+vm.data.areaId+
                            '","city_id" : "'+vm.data.cityId+'","mobile" : "'+vm.data.mobile+
                            '","name" : "'+vm.data.name+'","description" : "'+vm.data.description+
                            '","organization" : "'+vm.data.organization+'","email" : "'+vm.data.email+'"}';
            }
            else {
                var provider = '{"address" : "'+vm.data.address+'","area_id" : "'+vm.data.area_id+
                            '","city_id" : "'+vm.data.city_id+'","mobile" : "'+vm.data.mobile+
                            '","name" : "'+vm.data.name+'","description" : "'+vm.data.description+
                            '","organization" : "'+vm.data.organization+'","email" : "'+vm.data.email+'"}';
            }
            CandidateService.CreateServiceProvider(provider)
                .then(function (response) {
                    if (response.success) {
                        FlashService.Success('Added successful', true);
                        $location.path('/manager');
                    } else {
                        FlashService.Error(response.message);
                        vm.dataLoading = false;
                    }
                });
            
        }
    }

})();