(function () {
    'use strict';

    angular
        .module('app')
        .controller('AddServiceController', AddServiceController);

    AddServiceController.$inject = ['UserService', '$location',  'CandidateService', '$routeParams', 'FlashService'];
    function AddServiceController(UserService, $location, CandidateService,  $routeParams, FlashService) {
        var vm = this;
        vm.serviceId = $routeParams.id;
        if(!isEmpty($routeParams)){ 
            vm.registered = true;
            vm.login = true;
            vm.service_img = true;
        }
        else {vm.registered = false;vm.login = false;vm.service_img = false;}
        vm.user = null;
        vm.inUser = null;
        vm.data = [];
        initController();
        function initController() {
            loadUser();
            
            if(vm.registered){
                getService(vm.serviceId);
            }
            else { getCategories();}
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
        function getService(id) {
            CandidateService.getService(id)
                .then(function (response) {
                    vm.service = response.service;
                    vm.data.pname = vm.service[0].name;
                    vm.data.pstatus = vm.service[0].status;
                    vm.data.pdescription = vm.service[0].description;
                    vm.data.ppic_id = vm.service[0].pic_id;
                    vm.data.pservice_img = vm.service[0].service_img;
                });
        }
        vm.uploadIcon = function(){
            
            CandidateService.uploadImg('pic_id').then(function (response) {
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
        vm.changeIcon = function(){
            vm.login = false;
        }
        vm.changeServiceImg = function(){
            vm.service_img = false;
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
            /*if(vm.registered){
                var provider = '{"name" : "'+vm.data.pname+'","pic_id" : "'+vm.data.ppic_id+
                            '","service_img" : "'+vm.data.pservice_img+'","status" : "'+vm.data.pstatus+
                            '","description" : "'+vm.data.pdescription+'"}';
            }
            else {
                if (vm.data.category_id == undefined){
                    alert("Please Select category");
                    vm.dataLoading = false;
                }
                else {
                    var provider = '{"name" : "'+vm.data.pname+'","pic_id" : "'+vm.data.ppic_id+
                            '","service_img" : "'+vm.data.pservice_img+'","status" : "'+vm.data.pstatus+
                            '","description" : "'+vm.data.pdescription+'","category_id" : "'+vm.data.category_id+'"}';
                }
            }*/
            if (vm.data.status == undefined){
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