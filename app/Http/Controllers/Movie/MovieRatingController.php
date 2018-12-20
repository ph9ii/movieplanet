<?php

namespace App\Http\Controllers\Movie;

use App\Movie;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class MovieRatingController extends ApiController
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
        $ratings = $movie->ratings;

        return $this->showAll($ratings);
    }
}