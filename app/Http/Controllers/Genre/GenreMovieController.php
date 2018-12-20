<?php

namespace App\Http\Controllers\Genre;

use App\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class GenreMovieController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function index(Genre $genre)
    {
        $movies = $genre->movies;

        return $this->showAll($movies);
    }
}
