<?php

namespace App;

use App\Movie;
use App\Transformers\ActorTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Actor extends Model
{
    use softDeletes;

    public $transformer = ActorTransformer::class;

	protected $dates = ['deleted_at'];
    
    protected $fillable = [
    	'name',
    	'description'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function movies()
    {
    	return $this->belongsToMany(Movie::class);
    }
}
