(function () {
    'use strict';

    angular
        .module('app')
        .controller('ServiceProviderController', ServiceProviderController);

    ServiceProviderController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function ServiceProviderController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        console.log($routeParams);
        if(!isEmpty($routeParams)){ 
            vm.registered = false;
            vm.data = [];
            vm.data.service = [];
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
        vm.example1model = []; 
        vm.selected = [];
        
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
        /*vm.onCategoryChange = function(itemSelected) {
            if (vm.selected.indexOf(itemSelected.id) == -1) {
                vm.selected.push(itemSelected.id);
                var item = '<span id="'+itemSelected.id+
                            '" style="width:250px;height:30px;background-color:#0095ff;margin:10px;padding:5px;">'+itemSelected.name+
                        '</span>';
                $('#selectedServices').append(item); 
                
            }               
        }*/
        //onclick="remove('+itemSelected.id+')"<i class="glyphicon glyphicon-remove fa-fw" style="cursor:pointer;"  ></i>
        vm.addServiceProvider = function() {
                       
            console.log("addServiceProvider function",vm.selected);
            vm.dataLoading = true;
            if(vm.registered == false){
                var city = vm.data.cityId;
                var area = vm.data.areaId;
                var provider = '{"address" : "'+vm.data.address+'","area_id" : "'+vm.data.areaId+
                            '","city_id" : "'+vm.data.cityId+'","mobile" : "'+vm.data.mobile+
                            '","name" : "'+vm.data.name+'","description" : "'+vm.data.description+
                            '","organization" : "'+vm.data.organization+'","email" : "'+vm.data.email+'"}';
            }
            else {
                
                var city = vm.data.city_id;
                var area = vm.data.area_id;
                var provider = '{"address" : "'+vm.data.address+'","area_id" : "'+vm.data.area_id+
                            '","city_id" : "'+vm.data.city_id+'","mobile" : "'+vm.data.mobile+
                            '","name" : "'+vm.data.name+'","description" : "'+vm.data.description+
                            '","organization" : "'+vm.data.organization+'","email" : "'+vm.data.email+'"}';
            }
            if(city == undefined){
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
                            $location.path('/serviceprovider/'+response.service_providers.id+'/service');
                            
                        } else {
                            FlashService.Error(response.message);
                            vm.dataLoading = false;
                        }
                    });
            }
            
        }
    }

})();