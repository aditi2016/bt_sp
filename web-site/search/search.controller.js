(function () {
    'use strict';

    angular
        .module('app')
        .controller('SearchController', SearchController);

    SearchController.$inject = ['UserService',  'CandidateService', '$rootScope', 'FlashService','$location','upload','$timeout'];
    function SearchController(UserService, CandidateService,  $rootScope, FlashService,$location,upload,$timeout) {
        var vm = this;

        vm.user = null;
        vm.worker = null;
        vm.inUser = null;
        vm.allUsers = [];
        vm.deleteUser = deleteUser;
        vm.loadUser = loadUser;
        vm.search = search;
        vm.recruited = 0;
        vm.in_queue = 0;
        vm.new = 0;
        vm.rejected = 0;

        vm.recruitedFilter = true;
        vm.in_queueFilter = true;
        vm.newFilter = true;
        vm.rejectedFilter = true;

        initController();

        function initController() {
          //  loadCurrentUser();
           // loadAllUsers();

        }

       function loadUser(){
            vm.inUser = UserService.GetInUser();
            console.log("in user",vm.inUser);


        }
        vm.logout = function(){
            vm.inUser = null;
            $location.path('#/login');
        };


        vm.filterIt = function(status){

            if(status == "all")  {

                vm.recruitedFilter = true;
                vm.in_queueFilter = true;
                vm.newFilter = true;
                vm.rejectedFilter = true;

            }
            if(status == "recruited")  {

              vm.recruitedFilter = true;
              vm.in_queueFilter = false;
              vm.newFilter = false;
              vm.rejectedFilter = false;

            }
            if(status == "in-queue")  {

                vm.recruitedFilter = false;
                vm.inqueueFilter = true;
                vm.newFilter = false;
                vm.rejectedFilter = false;

            }
            if(status == "new")  {

                vm.recruitedFilter = false;
                vm.in_queueFilter = false;
                vm.newFilter = true;
                vm.rejectedFilter = false;

            }
            if(status == "rejected")  {

                vm.recruitedFilter = false;
                vm.in_queueFilter = false;
                vm.newFilter = false;
                vm.rejectedFilter = true;

            }

        };

        function loadAllUsers() {
            UserService.GetAll()
                .then(function (users) {
                    vm.allUsers = users;
                });
        }

        function deleteUser(id) {
            UserService.Delete(id)
            .then(function () {
                loadAllUsers();
            });
        }
/*

        vm.dtOptions = DTOptionsBuilder.newOptions()
            .withPaginationType('full_numbers')
            .withDisplayLength(2)
            .withDOM('pitrfl')
            .withOption('order', [, ]);
*/


        vm.loadMobile = function (index){
            console.log("load by mobile called",index,vm.toCallCandidates[index]);
            vm.toCallCandidates[index].mobile = parseInt(vm.toCallCandidates[index].mobile);
            vm.toCallCandidates[index].age = parseInt(vm.toCallCandidates[index].age);
            vm.user = vm.toCallCandidates[index];

        }

        function search() {

            vm.dataLoading = true;
            console.log("search function");
            CandidateService.SearchWorker(vm.worker)
                .then(function (response) {
                    console.log("safa",response);
                    if (response.root.worker) {
                        vm.searchworker = {};
                        vm.dataLoading = false;
                        vm.searchworker = response.root.worker;
                        console.log(vm.searchworker);
                        //loadToCallCandidates();
                        //$location.path('/login');
                    } else {
                        FlashService.Error("Failed to insert");
                        vm.dataLoading = false;
                    }
                });

        }
    }

})();