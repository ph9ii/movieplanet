<?php

namespace App\Http\Controllers\Movie;

use App\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Transformers\MovieTransformer;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiController;

class MovieController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);

        $this->middleware('transform.input:'. MovieTransformer::class)
            ->only(['store', 'update']);

        $this->middleware('client.credentials')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movies = Movie::all();

        return $this->showAll($movies);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {
        return $this->showOne($movie);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Allow action for admin user
        $this->allowedAdminAction();

        $rules = [
            'title'         => 'required|max:191',
            'description'   => 'required|max:1000',
            'gross_profit'  => 'required|max:191',
            'year_id'       => 'required|exists:years,id',
            'director_id'   => 'required|exists:directors,id',
            'image'         => 'required|image'
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['status'] = Movie::UNAVAILABLE_MOVIE;
        $data['image'] = $request->image->store('', 'images');

        $movie = Movie::create($data);

        return $this->showOne($movie, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {
        $this->allowedAdminAction();

        $locale = App::getLocale();

        // Update translation based on local language
        if($locale != 'en') {
            $this->updateTranslation($request, $movie, $locale);
        }

        if($locale == 'en') {
            $rules = [
                'image' => 'image',
                'year_id'       => 'exists:years,id',
                'director_id'   => 'exists:directors,id',
                'status' => 'in' . Movie::AVAILABLE_MOVIE . ',' . Movie::UNAVAILABLE_MOVIE
            ];

            $this->validate($request, $rules);

            $movie->fill($request->intersect([
                'title',
                'description',
                'gross_profit',
                'year_id',
                'director_id'
            ]));

            if($request->has('status')) {
                $movie->status = $request->status;

                if($movie->genres()->count() == 0) {
                    return $this->errorResponse('An active movie must have at least one genre', 409);
                }

                if($movie->actors()->count() == 0) {
                    return $this->errorResponse('An active movie must have at least one actor', 409);
                }
            }

            if($request->hasFile('image')) {
                Storage::delete($movie->image);
                $movie->image = $request->image->store('', 'images');
            }

            if($movie->isClean()) {
                return $this->errorResponse('You must specify a different value to update', 422);
            }

            $movie->save();
        }

        return $this->showOne($movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $this->allowedAdminAction();
        
        Storage::delete($movie->image);

        $movie->delete();

        return $this->showOne($movie);
    }

    /**
     * Update translation based on local language
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @param  string  $local
     * @return \Illuminate\Http\Response
     */
    protected function updateTranslation($request, $movie, $locale)
    {
        $attributes = ['translatable_id'=> $movie->id, 'language'=> $locale];

        if($movie->translations()->where($attributes)->exists()) {

            if($request->has('description')) {

                $movie->translation->update([
                    'content' => [
                        'description' => $request->description
                    ],
                ]);

                $movie->translation->save();
            }
        }

        if(!$movie->translations()->where($attributes)->exists()) {

            if($request->has('description')) {

                $movie->translations()->create([
                    'language' => $locale,
                    'content' => [
                        'description' => $request->description
                    ],
                ]);
            }
        }
    }
}
