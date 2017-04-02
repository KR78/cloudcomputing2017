'use strict';

angular.module('cloudcomputing2017App')
  .controller('DashboardCtrl', function ($scope, Restangular, $state, $auth) {

    Restangular.one('get/folders').get('') // GET: localhost:8000/api/v1/me
        .then(function(response) {
            $scope.folders = response.data // first Restangular obj in list: { id: 123 }
        });

    $scope.newFolders = {
      folder_name: ''
    };

	$scope.addFolderText = "Add New Folder";

	$scope.createNew = function(){
		$scope.folders.push({Folder: $scope.folderName});
		$scope.folderName = "";
	};

  $scope.createNewFolder = function(){
    console.log($scope.newFolders);
    Restangular.one('').post('create/folder', $scope.newFolders) // GET: localhost:8000/api/v1/me
      .then(function(response) {
          $scope.folders = response.data // first Restangular obj in list: { id: 123 }
      });
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
