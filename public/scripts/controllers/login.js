'use strict';

angular.module('cloudcomputing2017App')
  .controller('LoginCtrl', function ($scope, $rootScope, $auth, sweetAlert, $state, Account, localStorageService) {
  	$(function() {

    $('#login-form-link').click(function(e) {
		$("#login-form").delay(100).fadeIn(100);
 		$("#register-form").fadeOut(100);
		$('#register-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	$('#register-form-link').click(function(e) {
		$("#register-form").delay(100).fadeIn(100);
 		$("#login-form").fadeOut(100);
		$('#login-form-link').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});

});

$scope.login = function() {
  $auth.login($scope.userSignIn)
    .then(function() {
      $scope.loginLoading = false;
      $scope.loginButtonText = 'Sign In';
      sweetAlert.swal({
        title: 'Welcome!',
        text: 'Create New Folders and Upload Your Files!',
        type: 'success',
        showCancelButton: false,
        timer: 4000,
        confirmButtonColor: "#263238",
        confirmButtonText: "Okay",
      });
      localStorageService.clearAll();
      $rootScope.user = Account.getProfile();
      $state.go('main');
    })
    .catch(function(response) {
      $scope.loginLoading = false;
      $scope.loginButtonText = 'Sign In';
      sweetAlert.swal({
        title: 'Error!',
        text: 'Wrong username or password',
        type: 'error',
        showCancelButton: false,
        confirmButtonColor: "#263238",
        confirmButtonText: "Okay",
      });
    });
};

$scope.userSignUp =
{
  name: '',
  email: '',
  username: '',
  password: '',
  confirmPassword: ''
};

$scope.signup = function() {
  $scope.signUploading = true;
  $scope.signUpButtonText = 'Please Wait...';
  $auth.signup($scope.userSignUp)
    .then(function(response) {
      $scope.signUploading = false;
      $scope.signUpButtonText = 'Get Started';
      sweetAlert.swal({
        title: 'Account Created!',
        text: 'You have been successfully registered, you can now login.',
        type: 'success',
        showCancelButton: false,
        timer: 4000,
        confirmButtonColor: "#263238",
        confirmButtonText: "Okay",
      });
      $("#login-form").delay(100).fadeIn(100);
      $("#register-form").fadeOut(100);
      $('#register-form-link').removeClass('active');
      $(this).addClass('active');
    })
    .catch(function(response) {
      $scope.signUploading = false;
      $scope.signUpButtonText = 'Get Started';
      sweetAlert.swal({
        title: 'Error!',
        text: response.data.error.message,
        type: 'error',
        showCancelButton: false,
        confirmButtonColor: '#263238',
        confirmButtonText: 'Okay',
        confirmButtonClass: 'btn btn-info'
      }).then(function(response) {
        if (response) {
          // Clicked 'OK'
        } else {
          // Clicked 'Cancel'
        }
      });
    });
};

  });
