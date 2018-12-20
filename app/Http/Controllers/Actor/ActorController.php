<?php

namespace App\Http\Controllers\Actor;

use App\Actor;
use Illuminate\Http\Request;
use App\Transformers\ActorTransformer;
use App\Http\Controllers\ApiController;

class ActorController extends ApiController
{
    public function __construct()
    {
        $this->middleware('transform.input:'. ActorTransformer::class)
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
        $actors = Actor::all();

        return $this->showAll($actors);
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

        $newActor = Actor::create($request->all());

        return $this->showOne($newActor, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function show(Actor $actor)
    {
        return $this->showOne($actor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Actor $actor)
    {
        $this->allowedAdminAction();

        $actor->fill($request->intersect([
            'name'
        ]));

        if($actor->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', 422);
        }

        $actor->save();

        return $this->showOne($actor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Actor  $actor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Actor $actor)
    {
        $this->allowedAdminAction();
        
        if($actor->movies()->exists()) {
            return $this->errorResponse('This actor belongs to existing movie(s)', 422);
        }else {
            $actor->delete();

            return $this->showOne($actor);
        }
    }
}
