(function () {
    'use strict';

    angular
        .module('app')
        .controller('ServiceProviderServiceController', ServiceProviderServiceController);

    ServiceProviderServiceController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function ServiceProviderServiceController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        vm.id = $routeParams.id;
        vm.user = null;
        vm.inUser = null;
        vm.selectedServices = [];
        vm.data = [];
        initController();

        function initController() {
            loadUser();
            getAllServices();

            /*angular.forEach(vm.serviceId, function(id) {
                
                getService(id);
            });*/            
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
        /*function showconfirmbox(id) {
            if ($window.confirm("Do you want to add more Services?"))
            $location.path('/serviceprovider/'+id+'/service');
            else
            $location.path('/serviceprovider/'+id+'/service');
        }*/
        vm.addService = function() {
            
            vm.dataLoading = true;
            var data = '{"services" : [{"price":"'+vm.data.price+'","id" : "'+vm.data.service+'","negotiable":"'+
                    vm.data.negotiable+'","hourly":"'+vm.data.hourly+'"}]}';
            if (vm.data.service == undefined){
                alert("Please Select service");
                vm.dataLoading = false;
            }
            else if (vm.data.hourly == undefined){
                alert("Please Select Price By Time Unit");
                vm.dataLoading = false;
            }
            else if (vm.data.negotiable == undefined){
                alert("Please Select Negotiable");
                vm.dataLoading = false;
            }
            else {
                console.log(data);
                CandidateService.CreateServiceProviderService(vm.id, data)
                    .then(function (response) {
                        if (response.services.id) {
                            FlashService.Success('Added successful', true);
                            vm.dataLoading = false;
                            $location.path('/serviceprovider/'+vm.id+'/service');
                        } else {
                            FlashService.Error(response.message);
                            vm.dataLoading = false;
                        }
                    });
                                    
            }
            
        }
    }

})();