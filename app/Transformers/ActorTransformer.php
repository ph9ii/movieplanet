<?php

namespace App\Transformers;

use App\Actor;
use League\Fractal\TransformerAbstract;

class ActorTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Actor $actor)
    {
        return [
            'identifier'    => (int)$actor->id,
            'actorName'     => (string)$actor->name,
            'creationDate'  => (string)$actor->created_at,
            'lastChange'    => (string)$actor->updated_at,
            'deletedDate'   => isset($actor->deleted_at) ? (string)$actor->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('actors.show', $actor->id),
                ],
                [
                    'rel' => 'movies',
                    'href' => route('actors.movies.index', $actor->id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'    => 'id',
            'actorName'     => 'name',
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
            'name'          => 'actorName',
            'created_at'    => 'creationDate',
            'updated_at'    => 'lastChange',
            'deleted_at'    => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
