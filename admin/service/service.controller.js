(function () {
    'use strict';

    angular
        .module('app')
        .controller('ServiceController', ServiceController);

    ServiceController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function ServiceController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        console.log($routeParams);
        
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
            
            CandidateService.CreateServiceProvider(vm.data)
                .then(function (response) {
                    if (response.service_providers.id) {
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