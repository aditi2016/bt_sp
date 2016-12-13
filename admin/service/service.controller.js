(function () {
    'use strict';

    angular
        .module('app')
        .controller('ServiceController', ServiceController);

    ServiceController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function ServiceController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        
        vm.user = null;
        vm.inUser = null;
        vm.data = [];
        initController();
        vm.oldCity = 0;
        function initController() {
            loadUser();
            getCategories();
        }
        function isEmpty(obj){
            return (Object.getOwnPropertyNames(obj).length === 0);
        }
        function loadUser(){
            vm.inUser = UserService.GetInUser();
            console.log("in user",vm.inUser);
        }
        function getCategories() {
            CandidateService.getCategories()
                .then(function (response) {
                    vm.categories = response.categories;
                    console.log(vm.categories.name);
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
            else if (vm.data.pic_id == undefined){
                alert("Please Upload service Icon");
                vm.dataLoading = false;
            }
            else if (vm.data.service_img == undefined){
                alert("Please Upload service image");
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