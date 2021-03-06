/**
 * Created by spider-ninja on 6/4/16.
 */
(function () {
    'use strict';

    angular
        .module('app')
        .factory('CandidateService', CandidateService);

    CandidateService.$inject = ['$http'];

    function CandidateService($http) {
        var service = {};
        var urlSP = "https://blueteam.in/sp_api";
        service.GetAll = GetAll;
        service.GetById = GetById;
        service.GetByManagerEmployeeId = GetByManagerEmployeeId;
        service.GetByUsername = GetByUsername;
        service.Create = Create;
        service.Update = Update;
        service.Delete = Delete;
        service.Search = Search;
        service.GetAllProfession = GetAllProfession;
        service.GetUserInstance = GetUserInstance;
        service.UpdateInstance = UpdateInstance;
        service.GetUserLast10Instance = GetUserLast10Instance;
        service.GetTodayUsage = GetTodayUsage;
        service.getCities = getCities;
        service.getCityAreas = getCityAreas;
        service.CreateServiceProvider = CreateServiceProvider;
        service.getCategories = getCategories;
        service.CreateService = CreateService;
        service.uploadImg = uploadImg;
        service.getAllServices = getAllServices;
        service.getService = getService;
        service.notInstallApps = notInstallApps;
        service.changeStatus = changeStatus;
        service.notUsingApps = notUsingApps;
        service.addWorker = addWorker;
        service.getAllServiceRequests = getAllServiceRequests;
        service.CreateServiceProviderService = CreateServiceProviderService;
        service.CreateServiceRequest = CreateServiceRequest;
        service.getInterestedServices = getInterestedServices;
        service.getSearchResults = getSearchResults;
        service.getAllCallDetails = getAllCallDetails;
        service.changeType = changeType;
        service.getRecentCall = getRecentCall;

        return service;

        function GetAll() {
            return $http
                        .get('https://blueteam.in/sp_api/services?type=not-found')
                        .then(handleSuccess, handleError('Error getting all users'));
        }

        function GetUserInstance(professionId,uType,month) {
            return $http
                .get('http://api.bulldog.shatkonlabs.com/profession/'+professionId+'/type/'+uType+"/instance?month=" +month)
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function GetUserLast10Instance(userId,uType) {
            return $http
                .get('http://api.bulldog.shatkonlabs.com/instance/'+userId+'/last10')
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function GetTodayUsage(userId) {
            return $http
                .get('http://api.bulldog.shatkonlabs.com/usage/'+userId)
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function GetAllProfession() {
            return $http
                .get('http://api.shatkonjobs.com/professions')
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function Search(userSearch) {

            console.log(userSearch);

            var conStr = "";
            if(userSearch.age != undefined) conStr += "&age=" + userSearch.age;
            if(userSearch.area != undefined) conStr += "&area=" + userSearch.area;
            if(userSearch.gender != undefined) conStr += "&gender=" + userSearch.gender;

            return $http
                .get('http://api.shatkonjobs.com/candidates/search?profession_id='
                                                        + userSearch.profession
                                                        + conStr
                )
                .then(handleSuccess, handleError('Error getting all users'));
        }

        function GetById(id) {
            return $http.get('/api/users/' + id).then(handleSuccess, handleError('Error getting user by id'));
        }

        function GetByManagerEmployeeId(id,month) {
            return $http.get('http://api.bulldog.shatkonlabs.com/companies/:company_id/managers/:manager_id/employees/' + id+ "?month=" +month).then(handleSuccess, handleError('Error getting user by id'));
        }

        function GetByUsername(username) {
            return $http.get('/api/users/' + username).then(handleSuccess, handleError('Error getting user by username'));
        }



        function Create(user) {
            return $http.post('http://api.shatkonjobs.com/candidates', user).then(handleSuccess, handleError('Error creating user'));
        }

        function Update(user) {
            return $http.put('http://api.shatkonjobs.com/candidates/' + user.id, user).then(handleSuccess, handleError('Error updating user'));
        }

        function UpdateInstance(instance) {
            return $http.post('http://api.bulldog.shatkonlabs.com/instance', instance).then(handleSuccess, handleError('Error updating user'));
        }
        function changeStatus(data) {
            return $http.post('https://blueteam.in/api/service_request/sr_id', data).then(handleSuccess, handleError('Error getting cities'));
        }
        function Delete(id) {
            return $http.delete('/api/users/' + id).then(handleSuccess, handleError('Error deleting user'));
        }

        // private functions

        function handleSuccess(res) {
            return res.data;
        }

        function handleError(error) {
            return function () {
                return { success: false, message: error };
            };
        }
        function getCities() {
            return $http.get(urlSP + '/cities').then(handleSuccess, handleError('Error getting cities'));
        }
        function changeType(id, data) {
            return $http.put(urlSP +'/mobac/'+ id, data).then(handleSuccess, handleError('Error updating user'));
        }
        function getAllCallDetails() {
            return $http.get(urlSP + '/mobac').then(handleSuccess, handleError('Error getting contacts'));
        }
        function getRecentCall() {
            return $http.get(urlSP + '/mobac/recent').then(handleSuccess, handleError('Error getting contacts'));
        }
        function getAllServices() {
            return $http.get(urlSP + '/services').then(handleSuccess, handleError('Error getting cities'));
        }
        function notInstallApps() {
            return $http.get(urlSP + '/service_provider?type=not_install').then(handleSuccess, handleError('Error getting cities'));
        }
        function notUsingApps() {
            return $http.get(urlSP + '/service_provider?type=not_using').then(handleSuccess, handleError('Error getting cities'));
        }
        function getAllServiceRequests(type, month) {
            return $http.get('https://blueteam.in/api/cem_mysr_month/3?status='+type+'&month='+month).then(handleSuccess, handleError('Error getting cities'));
        }
        function getService(id) {
            return $http.get(urlSP + '/services/'+id).then(handleSuccess, handleError('Error getting cities'));
        }
        function getCategories() {
            return $http.get(urlSP + '/category').then(handleSuccess, handleError('Error getting cities'));
        }
        function getCityAreas(id) {
            return $http.get(urlSP + '/cities/'+id+'/areas').then(handleSuccess, handleError('Error getting areas'));
        }
        function getInterestedServices() {
            return $http.get(urlSP + '/interest').then(handleSuccess, handleError('Error getting areas'));
        }
        function getSearchResults() {
            return $http.get(urlSP + '/search').then(handleSuccess, handleError('Error getting areas'));
        }
        function CreateServiceProvider(user) {
            return $http.post(urlSP + '/service_provider', user).then(handleSuccess, handleError('Error creating user'));
        }
        function CreateServiceRequest(data) {
            return $http.post('https://blueteam.in/api/service_request', data).then(handleSuccess, handleError('Error getting cities'));
        }
        function CreateServiceProviderService(id,user) {
            return $http.post(urlSP + '/service_provider/'+id+'/services', user).then(handleSuccess, handleError('Error creating user'));
        }
        function CreateService(user) {
            return $http.post(urlSP + '/services', user).then(handleSuccess, handleError('Error creating user'));
        }
        function addWorker(data, societyId) {
            
            return $http.post('http://blueteam.in/api/society/'+societyId+'/addWorker', data).then(handleSuccess, handleError('Error creating user'));
        }
        function uploadImg(id) {
            var fileUrl = document.getElementById(id);
            var data = new FormData();
            data.append('fileToUpload', fileUrl.files[0]);
            return $http.post("http://api.file-dog.shatkonlabs.com/files/rahul", data, { 
                  transformRequest: angular.identity,
                  headers: {'Content-Type': undefined} }).then(handleSuccess, handleError('Error uploading file'));
            /*var request = new XMLHttpRequest();
            var responceTx = "";
            request.onreadystatechange = function(){
                if(request.readyState == 4){
                    responceTx = request.response;                    
                }
            };
            request.open('POST', 'http://api.file-dog.shatkonlabs.com/files/rahul');
            request.send(data);
            return responceTx;*/
        }
    }

})();
