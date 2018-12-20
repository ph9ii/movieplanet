<?php

namespace App\Http\Controllers\Rating;

use App\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class RatingController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Allow action for admin user
        // $this->allowedAdminAction();

        $ratings = Rating::all();

        return $this->showAll($ratings);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rating  $rating
     * @return \Illuminate\Http\Response
     */
    public function show(Rating $rating)
    {
        return $this->showOne($rating);
    }
}
