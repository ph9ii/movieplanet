<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoviesFeatureTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp()
    {
        parent::setUp();

        $this->artisan('passport:install');

        $this->movie = create('App\Movie');
    }

    /**
     * Only an authanticated user can see movies.
     *
     * @return void
     */
    public function test_Only_Auth_User_Can_SeeMovies()
    {
        $this->withExceptionHandling();

        $this->get('api/movies')
            ->assertStatus(401);

        $user = create('App\User');
        
        $token = $user->createToken('TestToken')->accessToken;

        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer '.$token;

        $data = [
                    "identifier" => $this->movie->id,
                    "movieName"  => $this->movie->title,
                    "details"    => $this->movie->description,
                ];

        $this->json('GET', 'api/movies/'.$this->movie->id, [], $header)
            ->assertJsonFragment($data);    
    }
}
