<?php

namespace App\Http\Controllers\Movie;

use App\Movie;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class MovieDirectorController extends ApiController
{
    public function __construct()
    {   
        $this->middleware('client.credentials')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function index(Movie $movie)
    {
        $director = $movie->director;

        return $this->showOne($director);
    }
}