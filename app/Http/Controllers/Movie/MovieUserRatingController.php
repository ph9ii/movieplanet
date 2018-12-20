<?php

namespace App\Http\Controllers\Movie;

use App\User;
use App\Movie;
use App\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Transformers\RatingTransformer;
use App\Http\Controllers\ApiController;


class MovieUserRatingController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'. RatingTransformer::class)
            ->only(['store']);

        $this->middleware('scope:rating-movie')->only(['store']);

        $this->middleware('can:rate,user')->only(['store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\User  $user
     * @param  \App\Movie  $movie
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Movie $movie, User $user)
    {
        $rules = [
            'rating' => 'required|integer|min:1|max:10'
        ];

        $this->validate($request, $rules);

        if(!$user->isVerified()) {
            return $this->errorResponse('The user must be a verified user', 409);
        }

        if(!$movie->isAvailable()) {
            return $this->errorResponse('The movie is not available', 409);
        }

        // Will rollback in case of any errors
         return DB::transaction(function() use ($request, $movie, $user) {

            $rating = Rating::firstOrCreate([
                'rating'     => $request->rating,
                'movie_id'   => $movie->id,
                'user_id'    => $user->id,
            ]);

            return $this->showOne($rating, 201);
        });
    }
}