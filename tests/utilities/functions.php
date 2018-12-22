<?php 

function create($class, $attributes = [], $times = null)
{
	return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [],$times = null)
{
	return factory($class, $times)->make($attributes);
}

function actingAsClient()
{
	return $this->withoutMiddleware(\Laravel\Passport\Http\Middleware\CheckClientCredentials::class);
}


