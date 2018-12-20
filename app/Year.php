<?php

namespace App;

use App\Movie;
use App\Transformers\YearTransformer;
use Illuminate\Database\Eloquent\Model;


class Year extends Model
{

	public $transformer = YearTransformer::class;

	public $hidden = [];

	public function movies()
	{
		return $this->hasMany(Movie::class);
	}
}
