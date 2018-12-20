<?php

namespace App\Transformers;

use App\Rating;
use League\Fractal\TransformerAbstract;

class RatingTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Rating $rating)
    {
        return [
            'identifier'    => (int) $rating->id,
            'rating'        => (int) $rating->rating,
            'userId'        => (int) $rating->user_id,
            'movieId'       => (int) $rating->movie_id,
            'creationDate'  => (string)$rating->created_at,
            'lastChange'    => (string)$rating->updated_at,
            'deletedDate'   => isset($rating->deleted_at) ? (string)$rating->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('ratings.show', $rating->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $rating->user_id),
                ],
                [
                    'rel' => 'movie',
                    'href' => route('movies.show', $rating->movie_id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'    => 'id',
            'rating'        => 'rating',
            'userId'        => 'user_id',
            'movieId'       => 'movie_id',
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
            'rating'        => 'rating',
            'user_id'       => 'userId',
            'movie_id'      => 'movieId',
            'created_at'    => 'creationDate',
            'updated_at'    => 'lastChange',
            'deleted_at'    => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
