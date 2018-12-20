<?php

namespace App\Http\Controllers\Actor;

use App\Actor;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ActorMovieController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function index(Actor $actor)
    {
        $movies = $actor->movies;

        return $this->showAll($movies);
    }
}
