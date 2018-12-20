<?php

namespace App\Transformers;

use App\Genre;
use League\Fractal\TransformerAbstract;

class GenreTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Genre $genre)
    {
        return [
            'identifier'    => (int)$genre->id,
            // 'genreName'     => (string)$genre->name,
            'genreName'     => (string)$genre->transResponse($genre, 'name'),
            'creationDate'  => (string)$genre->created_at,
            'lastChange'    => (string)$genre->updated_at,
            'deletedDate'   => isset($genre->deleted_at) ? (string)$genre->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('genres.show', $genre->id),
                ],
                [
                    'rel' => 'movies',
                    'href' => route('genres.movies.index', $genre->id),
                ]
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'    => 'id',
            'genreName'     => 'name',
            'creationDate'  => 'created_at',
            'lastChange'    => 'updated_at',
            'deletedDate'   => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'         => 'identifier',
            'name'       => 'genreName',
            'created_at' => 'creationDate',
            'updated_at' => 'lastChange',
            'deleted_at' => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
