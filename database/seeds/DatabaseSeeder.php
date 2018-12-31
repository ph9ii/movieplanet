<?php

use App\Year;
use App\User;
use App\Movie;
use App\Genre;
use App\Actor;
use App\Rating;
use App\Director;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Actor::truncate();
        Director::truncate();
        Genre::truncate();
        Movie::truncate();
        User::truncate();
        
        DB::table('ratings')->truncate();
        DB::table('translations')->truncate();
        DB::table('genre_movie')->truncate();
        DB::table('actor_movie')->truncate();

        
        $yearsQuantity     = 30;
        $genresQuantity    = 30;
        $actorsQuantity    = 300;
        $usersQuantity     = 100;
        $moviesQuantity    = 200;
        $ratingsQuantity   = 200;
        $directorsQuantity = 150;

        factory(User::class, $usersQuantity)->create();

        factory(Year::class, $yearsQuantity)->create();

        factory(Actor::class, $actorsQuantity)->create();

        factory(Director::class, $directorsQuantity)->create();

        factory(Genre::class, $genresQuantity)->create()->each(
            function ($genre) {
                $genre->translations()->create([
                    'language' => 'fr',
                    'content' => [
                        'name' => 'This Text translated as dummy fr'
                    ],
                ]);

                $genre->translations()->create([
                    'language' => 'de',
                    'content' => [
                        'name' => 'This Text translated as dummy de'
                    ],
                ]);
            }
        );

        factory(Movie::class, $moviesQuantity)->create()->each(
        	function ($movie) {
        		$genres = Genre::all()->random(mt_rand(1, 5))
                    ->pluck('id');

        		$movie->genres()->attach($genres);

                $actors = Actor::all()->random(mt_rand(1, 5))
                    ->pluck('id');

                $movie->actors()->attach($actors);

                $movie->translations()->create([
                    'language' => 'de',
                    'content' => [
                        'description' => 'This Text translated as dummy de'
                    ],
                ]);

                $movie->translations()->create([
                    'language' => 'fr',
                    'content' => [
                        'description' => 'This Text translated as dummy fr'
                    ],
                ]);
        	});

        factory(Rating::class, $ratingsQuantity)->create();
    }
}
