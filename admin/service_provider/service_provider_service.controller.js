(function () {
    'use strict';

    angular
        .module('app')
        .controller('ServiceProviderServiceController', ServiceProviderServiceController);

    ServiceProviderServiceController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function ServiceProviderServiceController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        vm.serviceId = $routeParams.serviceId.split(",");
        console.log(vm.serviceId);
        vm.id = $routeParams.id;
        vm.user = null;
        vm.inUser = null;
        vm.data = [];
        vm.selectedServices = [];
        initController();
        vm.oldCity = 0;
        function initController() {
            loadUser();
            angular.forEach(vm.serviceId, function(id) {
                console.log(id);
                getService(id);
            });
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
                    console.log(vm.selectedServices);
                });
        }
        vm.uploadIcon = function(){
           CandidateService.uploadImg('pic_id')
                .then(function (response) {
                    if (response.file.id) {
                        vm.data.pic_id = response.file.id;
                        console.log(vm.data.pic_id);
                        return false;
                    } else {
                        FlashService.Error(response.message);
                        return false;
                    }
                }); 
        }
        
        vm.uploadServiceImg = function(){
           CandidateService.uploadImg('service_img')
                .then(function (response) {
                    if (response.file.id) {
                        vm.data.service_img = response.file.id;
                        return false;
                    } else {
                        FlashService.Error(response.message);
                        return false;
                    }
                }); 
        }

        vm.addService = function() {
            console.log("addService function",vm.data);
            vm.dataLoading = true;
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
                                    
            }
            
        }
    }

})();