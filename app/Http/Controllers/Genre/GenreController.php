<?php

namespace App\Http\Controllers\Genre;

use App\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Transformers\GenreTransformer;
use App\Http\Controllers\ApiController;

class GenreController extends ApiController
{
    public function __construct()
    {
        $this->middleware('transform.input:'. GenreTransformer::class)
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
        $genres = Genre::all();

        return $this->showAll($genres);
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

        $newGenre = Genre::create($request->all());

        return $this->showOne($newGenre, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function show(Genre $genre)
    {
        return $this->showOne($genre);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Genre $genre)
    {
        $this->allowedAdminAction();

        $locale = App::getLocale();

        // Update translation based on local language
        if($locale != 'en') {
            $this->updateTranslation($request, $genre, $locale);
        }

        if($locale == 'en') {
            $genre->fill($request->intersect([
                'name'
            ]));

            if($genre->isClean()) {
                return $this->errorResponse('You need to specify any different value to update', 422);
            }

            $genre->save();
        }

        return $this->showOne($genre);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Genre $genre)
    {
        $this->allowedAdminAction();

        if($genre->movies()->exists()) {
            return $this->errorResponse('This genre belongs to existing movie(s)', 422);
        }else {
            $genre->delete();
            
            return $this->showOne($genre);
        }
    }

    /**
     * Update translation based on local language
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @param  string  $local
     * @return \Illuminate\Http\Response
     */
    protected function updateTranslation($request, $genre, $locale)
    {
        $attributes = ['translatable_id'=> $genre->id, 'language'=> $locale];

        if($genre->translations()->where($attributes)->exists()) {

            if($request->has('name')) {

                $genre->translation->update([
                    'content' => [
                        'name' => $request->name
                    ],
                ]);

                $genre->translation->save();
            }
        }

        if(!$genre->translations()->where($attributes)->exists()) {

            if($request->has('name')) {

                $genre->translations()->create([
                    'language' => $locale,
                    'content' => [
                        'name' => $request->name
                    ],
                ]);
            }
        }
    }
}
