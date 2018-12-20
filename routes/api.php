<?php

use Illuminate\Http\Request;

/**
 * OAuth
 */
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

/**
 * Users
 */
Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);

Route::resource('users.ratings', 'User\UserRatingController', 
	['only' => ['index']]);

Route::name('verify')->get('users/verify/{token}', 'User\UserController@verify');

/**
 * Genres
 */
Route::resource('genres', 'Genre\GenreController', 
	['except' => ['create', 'edit']]);

Route::resource('genres.movies', 'Genre\GenreMovieController', 
	['only' => ['index']]);

/**
 * Actors
 */
Route::resource('actors', 'Actor\ActorController', 
	['except' => ['create', 'edit']]);

Route::resource('actors.movies', 'Actor\ActorMovieController', 
	['only' => ['index']]);

/**
 * Movies
 */
Route::resource('movies', 'Movie\MovieController');

Route::resource('movies.genres', 'Movie\MovieGenreController', 
	['only' => ['index' , 'update', 'destroy']]);

Route::resource('movies.ratings', 'Movie\MovieRatingController',
	['only' => ['index']]);

Route::resource('movies.actors', 'Movie\MovieActorController',
	['only' => ['index' , 'update', 'destroy']]);

Route::resource('movies.years', 'Movie\MovieYearController',
	['only' => ['index']]);

Route::resource('movies.directors', 'Movie\MovieDirectorController',
	['only' => ['index']]);

Route::resource('movies.users.ratings', 'Movie\MovieUserRatingController', 
	['only' => ['store']]);


/**
 * Years
 */
Route::resource('years', 'Year\YearController',
	['except' => ['create', 'edit']]);

Route::resource('years.movies', 'Year\YearMovieController',
	['only' => ['index']]);

/**
 * Directors
 */
Route::resource('directors', 'Director\DirectorController',
	['except' => ['create', 'edit']]);

Route::resource('directors.movies', 'Director\DirectorMovieController', 
	['only' => ['index']]);

/**
 * Ratings
 */
Route::resource('ratings', 'Rating\RatingController',
	['only' => ['index', 'show']]);









