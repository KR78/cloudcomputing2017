<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Course;

class CoursesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

    }

    public function getAllCourses(){
      $courses = Course::all();

      return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
        'data' => ['courses' => $courses]
      ]);
    }


}
