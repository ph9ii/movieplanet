<?php

namespace App\Http\Controllers\Director;

use App\Director;
use Illuminate\Http\Request;
use App\Transformers\DirectorTransformer;
use App\Http\Controllers\ApiController;

class DirectorController extends ApiController
{
    public function __construct()
    {
        $this->middleware('transform.input:'. DirectorTransformer::class)
            ->only(['store', 'update']);

        $this->middleware('client.credentials')->only(['index', 'show']);
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $directors = Director::all();

        return $this->showAll($directors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->allowedAdminAction();

        $rules = [
            'name' => 'required'
        ];

        $this->validate($request, $rules);

        $newDirector = Director::create($request->all());

        return $this->showOne($newDirector, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Director  $director
     * @return \Illuminate\Http\Response
     */
    public function show(Director $director)
    {
        return $this->showOne($director);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Director  $director
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Director $director)
    {
        $this->allowedAdminAction();

        $director->fill($request->intersect([
            'name'
        ]));

        if($director->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', 422);
        }

        $director->save();

        return $this->showOne($director);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Director  $director
     * @return \Illuminate\Http\Response
     */
    public function destroy(Director $director)
    {
        $this->allowedAdminAction();

        if($director->movies()->exists()) {
            return $this->errorResponse('This director belongs to existing movie(s)', 422);
        }else {
            $director->delete();

            return $this->showOne($director);
        }     
    }
}
