'use strict';

angular.module('cloudcomputing2017App')
  .controller('AccountCtrl', function ($scope, $rootScope, Restangular, $auth, $state) {

    Restangular.one('me').get('') // GET: localhost:8000/api/v1/me
      .then(function(user) {
          $rootScope.user = user.data; // first Restangular obj in list: { id: 123 }
      });

      if (!$auth.isAuthenticated()) { // If state has auth: true && token has expired or is missing
        event.preventDefault(); // Prevent state/page from opening
        sweetAlert.swal({
          title: 'Sign In!',
          text: 'Please sign in to continue.',
          type: 'warning',
          showCancelButton: false,
          confirmButtonColor: "#263238",
          confirmButtonText: "Okay",
        }).then(function(response) {
          if (response) {
            $state.go('login');
          } else {
            $state.go('login');
          }
        });
        return $state.go('login'); // Redirect user to the home page
      }

   console.log($scope.user);
  });
