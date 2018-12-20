<?php

namespace App;

use App\User;
use App\Movie;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\RatingTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rating extends Model
{
    use softDeletes;

    public $transformer = RatingTransformer::class;
    protected $dates = ['deleted_at'];
    protected $fillable = [
    	'rating',
    	'user_id',
    	'movie_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
