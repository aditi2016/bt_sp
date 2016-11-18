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

        service.GetAll = GetAll;
        service.GetById = GetById;
        service.GetByUsername = GetByUsername;
        service.Create = Create;
        service.Update = Update;
        service.Delete = Delete;
        service.Search = Search;
        service.SearchWorker = SearchWorker;
        service.GetAllProfession = GetAllProfession;

        return service;

        function GetAll(society_id) {
            return $http
                        .get('http://api.bluenet.blueteam.in/workers/society?society_id='+society_id)
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

        function SearchWorker(user) {

            console.log(user);
            var root = {};
            root['root'] = user;
            return $http
                .get('http://blueteam.in/api/search?id_number='+user.id_number+'&id_type='+user.id_name, root)
                .then(handleSuccess, handleError('Error in search'));
        }
        function GetById(id) {
            return $http.get('/api/users/' + id).then(handleSuccess, handleError('Error getting user by id'));
        }

        function GetByUsername(username) {
            return $http.get('/api/users/' + username).then(handleSuccess, handleError('Error getting user by username'));
        }



        function Create(user) {
            
            return $http.post('http://api.sp.blueteam.in/service_provider', user).then(handleSuccess, handleError('Error creating user'));
        }

        function Update(user) {
            return $http.put('http://api.shatkonjobs.com/candidates/' + user.id, user).then(handleSuccess, handleError('Error updating user'));
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
    }

})();
