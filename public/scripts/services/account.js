(function() {

  'use strict';

  angular.module('cloudcomputing2017App')
    .factory('Account', ['Restangular', '$rootScope', 'localStorageService', 'sweetAlert',
      function(Restangular, localStorageService, $rootScope, sweetAlert) {
        return {
          getProfile: function() {
            // Restangular returns promises
            return Restangular.one('me').get('') // GET: localhost:8000/api/v1/me
              .then(function(user) {
                console.log(user.data);
                // returns a list of users
                // localStorageService.remove('user');
                // localStorageService.set('user', user.data);
                // $rootScope.user = localStorageService.get('user');
                return user.data; // first Restangular obj in list: { id: 123 }
              });
          },
          updateProfile: function(profileData) {
            return pbAppRestBasic.one('').post('me', profileData).then(
              function(response) {
                console.log(response);
                sweetAlert.swal({
                  title: 'Success!',
                  text: response.data.success.message,
                  type: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#263238',
                  confirmButtonText: 'Okay',
                });
                localStorageService.set('user', profileData);
                $rootScope.user = localStorageService.get('user');
              },
              function(response) {
                sweetAlert.swal({
                  title: 'Error!',
                  text: response.data.error.message,
                  type: 'error',
                  showCancelButton: false,
                  confirmButtonColor: '#263238',
                  confirmButtonText: 'Okay',
                });
                console.log(response.data.error.message);
              });
          }
        };
      }
    ]);

})();
