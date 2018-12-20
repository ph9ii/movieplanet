<?php

namespace App;

use App\Movie;
use App\Traits\Translatable;
use App\Transformers\GenreTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
	use softDeletes, Translatable;

    public $transformer = GenreTransformer::class;
    
	protected $dates = ['deleted_at'];

    protected $fillable = [
    	'name'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function movies()
    {
    	return $this->belongsToMany(Movie::class);
    }
}
