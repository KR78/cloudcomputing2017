<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Module;

class ModulesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

    }

    public function getAllModules(){
      $modules = Module::all();

      return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
        'data' => ['modules' => $modules]
      ]);

    }


}
