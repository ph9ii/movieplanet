<?php

namespace App\Http\Controllers\Year;

use App\Year;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class YearMovieController extends ApiController
{
    public function __construct()
    {   
        $this->middleware('client.credentials')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function index(Year $year)
    {
        $movies = $year->movies;

        return $this->showAll($movies);
    }
}