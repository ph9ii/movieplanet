<?php

namespace App;

use App\Year;
use App\Genre;
use App\Actor;
use App\Rating;
use App\Director;
use App\Traits\Translatable;
use Illuminate\Support\Facades\DB;
use App\Transformers\MovieTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use softDeletes, Translatable;

    const AVAILABLE_MOVIE = 'available';
    const UNAVAILABLE_MOVIE = 'unavailable';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('ratingCount', function ($builder) {
            $builder->withCount('ratings');
        });

        static::deleting(function ($movie) {
            $movie->ratings->each->delete();
            $movie->translations->each->delete();
        });
    }

    public $transformer = MovieTransformer::class;

    protected $dates = ['deleted_at'];

    protected $fillable = [
    	'title',
    	'description',
    	'gross_profit',
    	'image',
    	'year_id',
    	'director_id'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = strtolower($title);
    }

    public function getTitleAttribute($title)
    {
        return ucwords($title);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class)
            ->select(['id', 'rating']);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class);
    }

    public function director()
    {
        return $this->belongsTo(Director::class)
            ->select(['id', 'name']);
    }

    public function releaseYear()
    {
        return $this->belongsTo(Year::class, 'year_id')
            ->select(['id', 'year']);
    }

    public function isAvailable()
    {
        return $this->status == Movie::AVAILABLE_MOVIE;
    }
}
