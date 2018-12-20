<?php

use App\Year;
use App\Test;
use App\User;
use App\Movie;
use App\Genre;
use App\Actor;
use App\Rating;
use App\Director;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker $faker) {
    $password = bcrypt('secret');

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        // 'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'password' => $password ?: $password, // secret
        'remember_token' => str_random(10),
        'verified' => $verified = $faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]),
        'verification_token' => $verified  == User::VERIFIED_USER ? null : User::generateVerificationCode(),
        'admin' => $faker->randomElement([User::ADMIN_USER, User::REGULAR_USER]),
    ];
});

$factory->define(Year::class, function (Faker $faker) {
    return [
        'year' => $faker->numberBetween(1990, 2018),
    ];
});

$factory->define(Director::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(Genre::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(Actor::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});

// Comment this block when unit testing
// $factory->define(Movie::class, function (Faker $faker) {

//     $year = Year::all()->random();
//     $director = Director::all()->random();

//     return [
//         'title' => $faker->word,
//         'description' => $faker->paragraph(1),
//         'gross_profit' => $faker->randomElement(['170M', '200M', '470M']),
//         'year_id' => $year->id,
//         'director_id' => $director->id,
//         'status' => $faker->randomElement([Movie::AVAILABLE_MOVIE, Movie::UNAVAILABLE_MOVIE]),
//         'image' => $faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
//     ];
// });

// Comment this block when unit testing
// $factory->define(Rating::class, function (Faker $faker) {
    
//     $movie = Movie::all()->random();
//     $user = User::all()->random();

//     return [
//         'rating' => $faker->numberBetween(0, 10),
//         'user_id' => $user->id,
//         'movie_id' => $movie->id,
//     ];
// });

// Use this for unit testing
$factory->define(Movie::class, function (Faker $faker) {

    return [
        'title' => $faker->word,
        'description' => $faker->paragraph(1),
        'gross_profit' => $faker->randomElement(['170M', '200M', '470M']),
        'year_id' => function() {
            return factory('App\Year')->create()->id;
        },
        'director_id' => function() {
            return factory('App\Director')->create()->id;
        },
        'status' => $faker->randomElement([Movie::AVAILABLE_MOVIE, Movie::UNAVAILABLE_MOVIE]),
        'image' => $faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
    ];
});

// Use this for unit testing
$factory->define(Rating::class, function (Faker $faker) {

    return [
        'rating' => $faker->numberBetween(0, 10),
        'user_id' => function() {
            return factory('App\User')->create()->id;
        },
        'movie_id' => function() {
            return factory('App\Movie')->create()->id;
        }
    ];
});





