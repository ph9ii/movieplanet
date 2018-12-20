<?php

namespace App\Http\Controllers\Year;

use App\Year;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\YearTransformer;

class YearController extends ApiController
{
    public function __construct()
    {   
        $this->middleware('transform.input:'. YearTransformer::class)
            ->only(['store', 'update']);
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('client.credentials')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $years = Year::all();

        return $this->showAll($years);
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
     * @param  \App\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function show(Year $year)
    {
        return $this->showOne($year);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Year $year)
    {
        $this->allowedAdminAction();

        $year->fill($request->intersect([
            'name'
        ]));

        if($year->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', 422);
        }

        $year->save();

        return $this->showOne($year);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Year  $year
     * @return \Illuminate\Http\Response
     */
    public function destroy(Year $year)
    {
        $this->allowedAdminAction();
        
        if($year->movies()->exists()) {
            return $this->errorResponse('This year belongs to an existing movie(s)', 422);
        }else {
            $year->delete();

            return $this->showOne($year);
        }  
    }
}