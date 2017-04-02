<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use Config;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use \Firebase\JWT\JWT;
use App\User;
use \Illuminate\Http\Response as IlluminateResponse;



class AuthenticateController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

    }
    /**
     * Get all users
     */
    public function index()
    {
    // Retrieve all the users in the database and return them
        $users = User::all();
        return $users;
    }

    /**
     * Create a User Token
     */
     protected function createToken($user)
     {
         $key = 'sgKTsf8V8zihPiMm37MdVr1VMcbiZ44s';
         $payload = [
             'sub' => $user->id,
             'iat' => time(),
             'exp' => time() + (60*360) // JWT Token Expiration time (one hour)
         ];
         return JWT::encode($payload, $key);
     }
     /**
      * Create a User Token
      */
    public function authenticate(Request $request)
    {
      $username = $request->input('username');
      $password = $request->input('password');
      $user = User::where('username', $username)->first();
      if (!$user)
      {
        return $this->setStatusCode(401)->respondWithError("Wrong username or password, please try again. Thank you.");
      }
      if (Hash::check($password, $user->password) && $user->active == 1)
      {
          unset($user->password);
          return response()->json(['token' => $this->createToken($user)]);
      }
      else
      {
        return $this->setStatusCode(401)->respondWithError("Wrong username or password, please try again. Thank you.");
      }
    }

    public function register(Request $request)
    {
      $validator = Validator::make($request->all(), [
          'name'              => 'required',
          'username'          => 'required|unique:users',
          'email'             => 'required|email',
          'password'          => 'required|min:4',
          'confirmPassword'   => 'required|same:password'
      ]);
      if ($validator->fails()) {
          return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($validator->getMessageBag()->first());
      }

      $user = new User;
      $user->name = $request->input('name');
      $user->username = $request->input('username');
      $user->avatar = 'https://api.adorable.io/avatars/40/abott@adorable.png';
      $user->email = $request->input('email');
      $user->password = Hash::make($request->input('password'));
      $user->active = 1;

      $user->save();

      return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
        'data' => 'You have been successfully registered, you can now login.'
      ]);

    }

}
