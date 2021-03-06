(function () {
    'use strict';

    angular
        .module('app')
        .controller('CallLogsController', CallLogsController);

    CallLogsController.$inject = ['UserService',  'CandidateService', '$rootScope', 'FlashService','$location', '$interval'];
    function CallLogsController(UserService, CandidateService,  $rootScope, FlashService,$location, $interval) {
        var vm = this;
        vm.registered = true;
        vm.requested = true;
        vm.user = null;
        vm.inUser = null;
        vm.allUsers = [];
        vm.data = [];
        vm.updateMobile = updateMobile;
        vm.lastId = 0;
        vm.stopAudio = stopAudio;
        var audio = new Audio('./tune.mp3');
        initController();
        $interval(getRecentCall, 60000);
        function initController() {
            loadUser();            
            getAllCallDetails();
            getRecentCall();
        }
        function getRecentCall() {
            CandidateService.getRecentCall()
                .then(function (response) {
                    if(paerseInt(response.mobiles[0].id) > vm.lastId){
                        vm.lastId = response.mobiles[0].id;
                        vm.recentCall = response.mobiles;
                        $("#recentCallModal").modal("show");
                        playAudio();
                    }
                    else{
                        console.log('error');
                    }
                });
        }
        function playAudio() {
            audio.loop  = true;
            audio.play();
        };
        function stopAudio() {
            console.log("hi");
            audio.pause();
            audio.currentTime = 0;
        }
        vm.logout = function(){
            vm.inUser = null;
            UserService.DeleteInUser();
            $location.path('#/login');
        };

        function loadUser(){
            vm.inUser = UserService.GetInUser();
            if(!vm.inUser.name)
                $location.path('/login');
            console.log("in user",vm.inUser);
        }
        
        function updateMobile(id, name, mobile, mobile_id) {
            stopAudio();
            $("#recentCallModal").modal("hide");
            vm.data.id = id;
            vm.data.name = name;
            vm.data.mobile = mobile;
            vm.data.mobile_id = mobile_id;
            $("#statusUpdate").modal("show");
        }
        vm.changeStatus = function() {
            if(vm.registered !== false){
                var data = '{"type": "'+vm.data.type+'","mobile": "'+ vm.data.mobile
                            +'", "mobile_id": "'+ vm.data.mobile_id+'","name": "'+ vm.data.name+'" }';
                CandidateService.changeType(vm.data.id, data)
                    .then(function (response) {
                        $("#statusUpdate").modal("hide");
                        $location.path('/addServiceRequests').search({'mobile': vm.data.mobile,'name': vm.data.name});
                    });
            }
            else {
                var data = '{"type": "'+vm.data.type+'","mobile": "'+ vm.data.mobile +'","name": "'+ vm.data.name
                            +'", "mobile_id": "'+ vm.data.mobile_id+'", "remarks": "'+ vm.data.remarks+'" }';
                CandidateService.changeType(vm.data.id, data)
                    .then(function (response) {
                        $("#statusUpdate").modal("hide");
                        getAllCallDetails();
                });
            }
        }
        vm.typeChanged = function(){
            vm.dataLoading = true;
            if(vm.data.type !== 'lead'){
                vm.registered = false;
                vm.requested = false;
            }
            else {
                vm.registered = true;
                vm.requested = false;
            }
        }
        function getAllCallDetails(){
            vm.dataLoading = true;
            CandidateService.getAllCallDetails()
                .then(function (response) {
                    vm.mobiles = response.mobiles;
                    vm.dataLoading = false;
                    console.log(vm.mobiles[0].id);
                });
        }
    }

})();