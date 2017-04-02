'use strict';

angular.module('cloudcomputing2017App')
  .controller('ModuleCtrl', function ($scope, $stateParams, Restangular, $state, $auth, sweetAlert) {

  	$scope.folder = $stateParams.id;

    $scope.getFolder = {
      folder_id: $scope.folder
    };

    Restangular.one('').post('get/folders/files', $scope.getFolder) // GET: localhost:8000/api/v1/me
      .then(function(response) {
          $scope.files = response.data; // first Restangular obj in list: { id: 123 }
      });

    $scope.deleteFile = function(file){
      $scope.getFile = {
        file: file
      };

      Restangular.one('').post('delete/file', $scope.getFile) // GET: localhost:8000/api/v1/me
        .then(function(response) {
          sweetAlert.swal({
            title: 'File Deleted!',
            text: 'File has been deleted successfully...',
            type: 'info',
            showCancelButton: false,
            timer: 4000,
            confirmButtonColor: "#263238",
            confirmButtonText: "Okay",
          });
            $scope.files = response.data; // first Restangular obj in list: { id: 123 }
        });
    };

    $scope.dropzoneConfig = {
      'options': { // passed into the Dropzone constructor
        'url': 'http://localhost:9000/api/v1/upload/files',
        'maxFilesize': 6000,
        'maxFiles': 5,
        'addRemoveLinks': true
      },
      'eventHandlers': {
        'sending': function(file, xhr, formData) {
          formData.append('folderId', $scope.folder);
          xhr.setRequestHeader('Authorization', 'Bearer: ' +
            $auth.getToken());
            sweetAlert.swal({
              title: 'Uploading File...',
              text: 'File is being uploaded, please wait for a confirmation message...',
              type: 'info',
              showCancelButton: false,
              timer: 4000,
              confirmButtonColor: "#263238",
              confirmButtonText: "Okay",
            });
        },
        'success': function(file, response) {
          sweetAlert.swal({
            title: 'Success!',
            text: 'File uploaded successfully',
            type: 'success',
            showCancelButton: false,
            timer: 2000,
            confirmButtonColor: "#263238",
            confirmButtonText: "Okay",
          });
          Restangular.one('').post('get/folders/files', $scope.getFolder) // GET: localhost:8000/api/v1/me
            .then(function(response) {
                $scope.files = response.data; // first Restangular obj in list: { id: 123 }
            });
        },
        'complete': function() {
        },
        'error': function() {
          sweetAlert.swal({
            title: 'Error!',
            text: 'Sorry, something happened while uploading your image, please try again.',
            type: 'error',
            showCancelButton: false,
            confirmButtonColor: '#263238',
            confirmButtonText: 'Okay',
          });
        },
        'maxfilesexceeded': function() {
          sweetAlert.swal({
            title: 'Info!',
            text: 'Please wait while we create a new bucket for you...',
            type: 'info',
            showCancelButton: false,
            confirmButtonColor: '#263238',
            confirmButtonText: 'Okay',
          });
        }
      }
    };

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

});
