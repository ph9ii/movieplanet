<?php

namespace App\Http\Controllers\Movie;

use App\Movie;
use App\Actor;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class MovieActorController extends ApiController
{
    public function __construct()
    {   
        $this->middleware('auth:api')->except(['index']);
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('scope:manage-movies')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function index(Movie $movie)
    {
        $actors = $movie->actors;

        return $this->showAll($actors);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Movie  $Movie
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function update(Movie $movie, Actor $actor)
    {
        $this->allowedAdminAction();

        $movie->actors()->syncWithoutDetaching([$actor->id]);

        return $this->showAll($movie->actors);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie, Actor $actor)
    {
        $this->allowedAdminAction();
        
        if(!$movie->actors()->find($actor->id)) {
            return $this->errorResponse('This actor is not related to this movie', 404);
        }

        $movie->actors()->detach($actor->id);

        return $this->showAll($movie->actors);
    }
}