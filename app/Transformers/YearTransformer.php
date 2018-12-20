<?php

namespace App\Transformers;

use App\Year;
use League\Fractal\TransformerAbstract;

class YearTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Year $year)
    {
        return [
            'identifier'    => (int)$year->id,
            'releaseYear'   => (int)$year->year,
            'creationDate'  => (string)$year->created_at,
            'lastChange'    => (string)$year->updated_at,
            'deletedDate'   => isset($year->deleted_at) ? (string)$year->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('years.show', $year->id),
                ],
                [
                    'rel' => 'movies',
                    'href' => route('years.movies.index', $year->id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier'    => 'id',
            'releaseYear'   => 'year',
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
            'year'          => 'releaseYear',
            'created_at'    => 'creationDate',
            'updated_at'    => 'lastChange',
            'deleted_at'    => 'deletedDate',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
