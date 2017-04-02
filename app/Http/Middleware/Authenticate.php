<?php
namespace App\Http\Middleware;

use Firebase\JWT\JWT;
use Config;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if ($request->header('Authorization'))
  		{
        $key = 'sgKTsf8V8zihPiMm37MdVr1VMcbiZ44s';
  			$token = explode(' ', $request->header('Authorization'))[1];
  			$payload = (array) JWT::decode($token, $key, array('HS256'));

  			if ($payload['exp'] < time())
  			{
  				return response()->json(['error' => ['message' => 'Token has expired']]);
  			}

  			$request['user'] = $payload;

  			return $next($request);
  		}
  		else
  		{
  			return response()->json(['error' => ['message' => 'Session expired, please log in to continue.']], 401);
  		}
    }
}
