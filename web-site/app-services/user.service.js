(function () {
    'use strict';

    angular
        .module('app')
        .factory('UserService', UserService);

    UserService.$inject = ['$http', '$cookieStore'];
    function UserService($http, $cookieStore) {
        var service = {};

        service.Auth = Auth;





        service.GetInUser = function(){

            return JSON.parse($cookieStore.get('inUser'));

        }

        function Auth(user) {
            var root = {};
            root['root'] = user;
            console.log(root);
            return $http.post('https://blueteam.in/api/login/society', root).then(handleSuccess, handleError('Error creating user'));
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
        return service;
    }

})();
