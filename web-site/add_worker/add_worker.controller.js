(function () {
    'use strict';

    angular
        .module('app')
        .controller('AddWorkerController', AddWorkerController);

    AddWorkerController.$inject = ['UserService',  'CandidateService', '$rootScope', 'FlashService','$location','upload','$timeout'];
    function AddWorkerController(UserService, CandidateService,  $rootScope, FlashService,$location,upload,$timeout) {
        var vm = this;

        vm.user = null;
        vm.worker = null;
        vm.inUser = null;
        vm.allUsers = [];
        vm.deleteUser = deleteUser;
        vm.loadUser = loadUser;

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

            loadUser();
            loadToCallCandidates();

        }

        function loadUser(){
            vm.inUser = UserService.GetInUser();
            console.log("in user",vm.inUser);


        }

        vm.loadToCallCandidates = loadToCallCandidates;


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

        function loadToCallCandidates(){
            vm.search = false;
            CandidateService.GetAll(vm.inUser.society_id)
                .then(function (response) {
                    vm.toCallCandidates = response.root.workers;
                    console.log(vm.toCallCandidates[1].name);
                    for(var i=0;i < vm.toCallCandidates.length ; i++){
                        vm.recruited += (vm.toCallCandidates[i].status == "recruited")?1:0;
                        vm.in_queue += (vm.toCallCandidates[i].status == "in-queue")?1:0;
                        vm.new += (vm.toCallCandidates[i].status == "new")?1:0;
                        vm.rejected += (vm.toCallCandidates[i].status == "rejected")?1:0;
                    }
                });

        }

        /*function loadCurrentUser() {
            UserService.GetByUsername($rootScope.globals.currentUser.username)
                .then(function (user) {
                    vm.user = user;
                });
        }*/

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


        vm.searchWorker = function () {
            console.log("searching Worker function");
            vm.dataLoading = true;

                CandidateService.Search(vm.userSearch)
                    .then(function (response) {
                        console.log("safa",response);
                        if (response.candidates) {
                            vm.dataLoading = false;
                            vm.user = null;
                            vm.toCallCandidates = response.candidates;

                        } else {
                            FlashService.Error(response.error.text);
                            vm.dataLoading = false;
                        }
                    });

        }

        vm.doUpload = function (file_id) {
            if(document.getElementById(file_id).files[0])
                upload({
                    url: 'http://api.file-dog.shatkonlabs.com/files/rahul',
                    method: 'POST',
                    data: {
                        anint: 123,
                        fileToUpload: document.getElementById(file_id).files[0], // a jqLite type="file" element, upload() will extract all the files from the input and put them into the FormData object before sending.
                    }
                }).then(
                    function (response) {
                        vm.filesUpStatus[file_id] = true;
                        document.getElementById(file_id).value = "";
                        console.log(response.data);
                        vm.worker["worker_"+file_id] = response.data.file.id;
                        console.log(vm.worker);
                    },
                    function (response) {
                        console.error(response); //  Will return if status code is above 200 and lower than 300, same as $http
                    }
                );
            else vm.filesUpStatus[file_id] = true;
        };

        vm.filesUpStatus = new Array();
        vm.filesUpStatus["vc"] = false;
        vm.filesUpStatus["ac"] = false;
        vm.filesUpStatus["pc"] = false;
        vm.filesUpStatus["photo"] = false;
        vm.filesUpStatus["dl"] = false;
        vm.filesUpStatus["pv"] = false;


        vm.flag = true;
        vm.registerWorker = function registerWorker() {

            vm.dataLoading = true;

            if(vm.flag) {
                vm.doUpload('vc');
                vm.doUpload('ac');
                vm.doUpload('pc');
                vm.doUpload('photo');
                vm.doUpload('dl');
                vm.doUpload('pv');
            }
            //while (true){

                if(
                    vm.filesUpStatus["vc"] &&
                    vm.filesUpStatus["ac"] &&
                    vm.filesUpStatus["pc"] &&
                    vm.filesUpStatus["photo"] &&
                    vm.filesUpStatus["dl"] &&
                    vm.filesUpStatus["pv"]

                ) vm.flag = true;
                else {

                    vm.flag = false;
                    $timeout(function () {
                        vm.registerWorker();
                    }, 3000);
                    return;
                }
            //}

            console.log("registerWorker function",vm.inUser.society_id);


            CandidateService.Create(vm.worker, vm.inUser.society_id)
                .then(function (response) {
                    console.log("safa",response);
                    if (response.root.worker_id) {
                        FlashService.Success('Registration successful', true);

                        vm.worker = {};
                        vm.dataLoading = false;
                        $scope.workerReg.$setUntouched();
                        $scope.workerReg.$setPristine();


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