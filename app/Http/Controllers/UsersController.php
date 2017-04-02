<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\File;
use App\Folder;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use \Illuminate\Http\Response as IlluminateResponse;
use App\User;
use Response;
use Validator;
use Illuminate\Support\Facades\Storage;

class UsersController extends ApiController
{
  /**
   * Function to upload user files.
   */
  public function postUploadFiles(Request $request){

    $validator = Validator::make($request->all(), [
        'file'                 => 'required|max:6000',
    ]);

    if ($validator->fails()) {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($validator->getMessageBag()->first());
    }

    $userId = $request['user']['sub'];
    $folderId = $request->input('folderId');

    // Check if the file exists in the request
    if(!$request->hasFile('file')){
      return $this->setStatusCode(402)->respondWithError('No file has been sent');
    }

    if(!$request->file('file')->isValid()){
      return $this->setStatusCode(402)->respondWithError('File is not valid');
    }

    $folder = Folder::where('name', $folderId)->where('user_id', $userId)->first();

    $s3 = Storage::disk('s3');
    $file = $request->file('file');
    $mimeType = $file->getClientMimeType();
    $fileSize = $file->getClientSize();
    $extension = $file->guessClientExtension();
    $fileName = $file->getClientOriginalName();

    $s3->put("user_{$userId}/files/" . $fileName, file_get_contents($file), 'public');

    $url = 'https://s3.eu-west-2.amazonaws.com/cloudcomputing2017project';

    $createFileLog = new File;
    $createFileLog->file_name = $fileName;
    $createFileLog->user_id = $userId;
    $createFileLog->folder_id = $folder->id;
    $createFileLog->mime_type = $mimeType;
    $createFileLog->file_size = $fileSize;
    $createFileLog->file_type = $extension;
    $createFileLog->file_path = $url . "/user_{$userId}/files/" . $fileName;
    $createFileLog->save();
  }

  /**
   * Function to get user files.
   */
  public function getUserFiles(Request $request){

    $files = File::where('user_id', $request['user']['sub'])->get();

    if(!$files){
      return $this->setStatusCode(404)->respondWithError('Currently no files available on record.');
    }

    return Response::json($files);
  }

  /**
   * Function to get user files.
   */
  public function getUserFolderFiles(Request $request){

    $userId = $request['user']['sub'];

    $folder = Folder::where('name', $request->input('folder_id'))->where('user_id', $userId)->first();

    $files = File::where('user_id', $userId)->where('folder_id', $folder->id)->get();

    if(!$files){
      return $this->setStatusCode(404)->respondWithError('Currently no files available on record.');
    }

    return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
      'data' => $files
    ]);
  }

  /**
   * Get signed in user's profile.
   */
  public function deleteFile(Request $request){

    $user = $request['user']['sub'];

    $s3 = Storage::disk('s3');
    $s3->delete($request->input('file'));
    $file = File::where('file_path', $request->input('file'))->first();
    $file->delete();

    return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
      'data' => ['message' => 'Image has been deleted successfully']
    ]);

  }

  /**
   * Get signed in user's profile.
   */
  public function getUser(Request $request)
  {
      $user = User::find($request['user']['sub']);

      return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
        'data' => $user
      ]);
  }

  /**
   * Update signed in user's profile.
   */
  public function updateUser(Request $request)
  {
      $user = User::find($request['user']['sub']);

      $validator = Validator::make($request->all(), [
          'name'         => 'required | regex:/^[A-Za-z0-9\-\s]+$/',
          'username'     => 'required | regex:/^[A-Za-z0-9\-\s]+$/ | unique:users,username,'.$request['user']['sub'],
          'email'        => 'unique:users,email,'.$request['user']['sub'],
          'gender'       => 'required',
      ]);

      if ($validator->fails()) {
          return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($validator->getMessageBag()->first());
      }

      $user = User::find($request['user']['sub']);

      $user->full_name = $request->input('full_name');
      $user->displayName = $request->input('displayName');
      $user->dob = $request->input('dob');
      $user->box_no = $request->input('box_no');
      $user->bio = $request->input('bio');
      $user->email = $request->input('email');
      $user->gender = $request->input('gender');
      $user->city = $request->input('city');
      $user->country = $request->input('country');
      $user->location = $request->input('location');
      $user->website = $request->input('website');
      $user->save();

      $token = $this->createToken($user);

      return response()->json(['token' => $token, 'data' => [
        'success' => ['message' => 'Profile updated successfully']
        ]]);
  }

  public function getUserFolders(Request $request){

    $userId = $request['user']['sub'];

    $folders = Folder::where('user_id', $userId)->get();

    return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
      'data' => $folders
    ]);

  }

  public function createUserFolder(Request $request){

    $userId = $request['user']['sub'];

    $newFolder = new Folder;

    $newFolder->name = $request->input('folder_name');
    $newFolder->user_id = $userId;
    $newFolder->save();

    $folders = Folder::where('user_id', $userId)->get();

    return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
      'data' => $folders
    ]);

  }
}
