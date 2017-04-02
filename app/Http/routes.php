<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
Remove the # from the url in AngularJs
*/

Route::get('/', function () {
return File::get(public_path().'/index.html');
});



Route::group(['prefix' => 'api/v1'], function()
{
    /**
     * GET Requests
     */
    // Get All Users
    Route::get('me', ['middleware' => 'auth', 'uses' => 'UsersController@getUser']);
    // Get All Users
    Route::get('users', 'AuthenticateController@index');
    // Get All Modules
    Route::get('modules', 'ModulesController@getAllModules');
    // Get All Users
    Route::get('courses', 'CoursesController@getAllCourses');
    // Get User Files
    Route::get('get/files', ['middleware' => 'auth', 'uses' => 'UsersController@getUserFiles']);
    // Get User Folders
    Route::get('get/folders', ['middleware' => 'auth', 'uses' => 'UsersController@getUserFolders']);

    /**
     * POST Requests
     */
    // Get All Users
    Route::post('me', ['middleware' => 'auth', 'uses' => 'UsersController@updateUser']);
    // Authenticate a user and generate a JSON Web Token
    Route::post('auth/login', 'AuthenticateController@authenticate');
    // Authenticate a user and generate a JSON Web Token
    Route::post('auth/signup', 'AuthenticateController@register');
    // Upload Files
    Route::post('upload/files', ['middleware' => 'auth', 'uses' => 'UsersController@postUploadFiles']);
    // Create User Folders
    Route::post('create/folder', ['middleware' => 'auth', 'uses' => 'UsersController@createUserFolder']);
    // Create User Folders
    Route::post('delete/file', ['middleware' => 'auth', 'uses' => 'UsersController@deleteFile']);
    // Get User Folders Files
    Route::post('get/folders/files', ['middleware' => 'auth', 'uses' => 'UsersController@getUserFolderFiles']);
});
