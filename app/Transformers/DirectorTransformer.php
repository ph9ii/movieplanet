<?php

namespace App\Transformers;

use App\Director;
use League\Fractal\TransformerAbstract;

class DirectorTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Director $director)
    {
        return [
            'identifier'    => (int)$director->id,
            'directorName'  => (string)$director->name,
            'creationDate'  => (string)$director->created_at,
            'lastChange'    => (string)$director->updated_at,
            'deletedDate'   => isset($director->deleted_at) ? (string)$director->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('directors.show', $director->id),
                ],
                [
                    'rel' => 'movies',
                    'href' => route('directors.movies.index', $director->id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'    => 'id',
            'directorName'  => 'name',
            'creationDate'  => 'created_at',
            'lastChange'    => 'updated_at',
            'deletedDate'   => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            'id'            => 'identifier',
            'name'          => 'directorName',
            'created_at'    => 'creationDate',
            'updated_at'    => 'lastChange',
            'deleted_at'    => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
