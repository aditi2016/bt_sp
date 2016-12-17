(function () {
    'use strict';

    angular
        .module('app')
        .controller('ServiceProviderServiceController', ServiceProviderServiceController);

    ServiceProviderServiceController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function ServiceProviderServiceController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        vm.serviceId = $routeParams.serviceId.split(",");
       
        vm.id = $routeParams.id;
        vm.user = null;
        vm.inUser = null;
        vm.selectedServices = [];
        vm.data = [];
        initController();

        function initController() {
            loadUser();
            angular.forEach(vm.serviceId, function(id) {
                
                getService(id);
            });
            //vm.selectedServices = JSON.stringify(vm.selectedServices);
            console.log(vm.selectedServices);
        }
        function isEmpty(obj){
            return (Object.getOwnPropertyNames(obj).length === 0);
        }
        function loadUser(){
            vm.inUser = UserService.GetInUser();
            console.log("in user",vm.inUser);
        }
        function getService(id) {
            CandidateService.getService(id)
                .then(function (response) {
                    vm.selectedServices.push(response.service);
                });
        }

        vm.addService = function() {
            console.log(vm.data);
            /*vm.dataLoading = true;
            if (vm.data.category_id == undefined){
                alert("Please Select Category");
                vm.dataLoading = false;
            }
            else if (vm.data.status == undefined){
                alert("Please Select Status");
                vm.dataLoading = false;
            }
            
            else {
                CandidateService.CreateService(vm.data)
                    .then(function (response) {
                        if (response.service.id) {
                            FlashService.Success('Added successful', true);
                            $location.path('/service');
                        } else {
                            FlashService.Error(response.message);
                            vm.dataLoading = false;
                        }
                    });
                                    
            }*/
            
        }
    }

})();