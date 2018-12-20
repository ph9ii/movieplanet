<?php

namespace App\Http\Controllers;

use App\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class TestController extends ApiController
{
    public function __construct()
    {   

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $test = Test::all();
        // locale()->current();
 
        // if(locale()->isSupported('en')) {
        //     return 'hi';
        // }
        // return TestTranslation::all();

        // return $test->translate('fr');

        \App::setLocale('su');

        $test = Test::all();

        return $test;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        // $locale = \App::getLocale();

        $test = Test::create([
            'name' => $request->name
        ]);

        // $test = new Test;

        // foreach (['en', 'de'] as $locale) {
        //     $test->translateOrNew($locale)->name = $request->name;
        // }

        // $data = [
        //     'code' => $locale,
        //     $locale  => ['name' => $request->name],
        // ];

        // $test = Test::create($data);

        // $test = $test->translate('en')->name;

        return $test;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {
        \App::setLocale('su');

        return $test->translation->content['name'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function edit(Test $test)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Test $test)
    {
        $this->validate($request, [
            'name' => 'string'
        ]);

        \App::setLocale('st');

        $locale = \App::getLocale();

        $attributes = ['translatable_id'=> $test->id, 'language'=> $locale];

        if($locale != 'en') {

            if($test->translations()->where($attributes)->exists()) {

                if($request->has('name')) {

                    $test->translation->update([
                        'content' => [
                        'name' => $request->name
                        ],
                    ]);

                    return $test->translation;
                }
            }

            if(!$test->translations()->where($attributes)->exists()) {

                if($request->has('name')) {
                    $test->translations()->create([
                        'language' => $locale,
                        'content' => [
                            'name' => $request->name
                        ],
                    ]);

                    return $test->translation;
                }
            }
        }

        if($request->has('name')) {
            $test->name = $request->name;
        }

        if(!$test->isDirty()) {
            
            return $this->errorResponse('You must specify different values to update', 422);
        } 

        $test->save();
 
        return $test;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test)
    {
        //
    }
}
