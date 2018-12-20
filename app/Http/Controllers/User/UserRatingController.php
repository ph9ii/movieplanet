<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserRatingController extends ApiController
{
    public function __construct()
    {   
        parent::__construct();
        $this->middleware('can:view,user')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $ratings = $user->ratings;

        return $this->showAll($ratings);
    }
}