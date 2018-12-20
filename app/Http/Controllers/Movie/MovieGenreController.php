<?php

namespace App\Http\Controllers\Movie;

use App\Movie;
use App\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class MovieGenreController extends ApiController
{
    public function __construct()
    {   
        $this->middleware('auth:api')->except(['index']);
        $this->middleware('client.credentials')->only(['index']);
        // $this->middleware('scope:manage-movies')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Movie $movie)
    {
        $genres = $movie->genres;

        return $this->showAll($genres);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Movie  $Movie
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function update(Movie $movie, Genre $genre)
    {
        $this->allowedAdminAction();

        $movie->genres()->syncWithoutDetaching([$genre->id]);

        return $this->showAll($movie->genres);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie, Genre $genre)
    {
        $this->allowedAdminAction();
        
        if(!$movie->genres()->find($genre->id)) {
            return $this->errorResponse('This genre is not related to this movie', 404);
        }

        $movie->genres()->detach($genre->id);

        return $this->showAll($movie->genres);
    }
}
