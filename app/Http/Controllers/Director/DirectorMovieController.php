<?php

namespace App\Http\Controllers\Director;

use App\Director;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class DirectorMovieController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Director  $director
     * @return \Illuminate\Http\Response
     */
    public function index(Director $director)
    {
        $movies = $director->movies;

        return $this->showAll($movies);
    }
}
