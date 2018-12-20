<?php

namespace App\Transformers;

use App\Movie;
use League\Fractal\TransformerAbstract;

class MovieTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Movie $movie)
    {
        return [
            'identifier'    => (int)$movie->id,
            'movieName'     => (string)$movie->title,
            // 'movieName'     => (string)$movie->transResponse($movie, 'title'),
            // 'details'       => (string)$movie->description,
            'details'       => (string)$movie->transResponse($movie, 'description'),
            'profit'        => (string)$movie->gross_profit,
            'yearId'        => (int)$movie->year_id,
            'directorId'    => (int)$movie->director_id,
            'directorName'  => (string)$movie->director->name,
            'availability'  => (string)$movie->status,
            'releaseYear'   => (int)$movie->releaseYear->year,
            'ratingsCount'  => (int)$movie->ratings_count,
            'ratingsAvg'    => (int)$movie->ratings()->avg('rating'),
            'picture'       => url("img/{$movie->image}"),
            'creationDate'  => (string)$movie->created_at,
            'lastChange'    => (string)$movie->updated_at,
            'deletedDate'   => isset($movie->deleted_at) ? (string)$movie->deleted_at : null,

            'links' => [
                [
                    'rel'  => 'self',
                    'href' => route('movies.show', $movie->id),
                ],
                [
                    'rel' => 'movie.directors',
                    'href' => route('movies.directors.index', $movie->id),
                ],
                [
                    'rel' => 'movie.years',
                    'href' => route('movies.years.index', $movie->id),
                ],
                [
                    'rel' => 'movie.genres',
                    'href' => route('movies.genres.index', $movie->id),
                ],
                [
                    'rel'  => 'movie.actors',
                    'href' => route('movies.actors.index', $movie->id),
                ],
                [
                    'rel' => 'movie.ratings',
                    'href' => route('movies.ratings.index', $movie->id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'    => 'id',
            'movieName'     => 'title',
            'details'       => 'description',
            'profit'        => 'gross_profit',
            'yearId'        => 'year_id',
            'availability'  => 'status',
            'releaseYear'   => 'releaseYear.year',
            'ratingsCount'  => 'ratings_count',
            'directorId'    => 'director_id',
            'directorName'  => 'director.name',
            'picture'       => 'image',
            'creationDate'  => 'created_at',
            'lastChange'    => 'updated_at',
            'deletedDate'   => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'                => 'identifier',
            'title'             => 'movieName',
            'description'       => 'details',
            'gross_profit'      => 'profit',
            'year_id'           => 'yearId',
            'status'            => 'availability',
            'releaseYear.year'  => 'releaseYear',
            'ratings_count'     => 'ratingsCount',
            'director_id'       => 'directorId',
            'director.name'     => 'directorName',
            'image'             => 'picture',
            'created_at'        => 'creationDate',
            'updated_at'        => 'lastChange',
            'deleted_at'        => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
