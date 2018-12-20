<?php

namespace App;

use App\Movie;
use App\Transformers\DirectorTransformer;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    public $transformer = DirectorTransformer::class;

    public function movies()
	{
		return $this->hasMany(Movie::class);
	}
}
