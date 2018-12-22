<?php

namespace Tests\Feature;

use App\User;
use App\Movie;
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

    /**
     * Only an authanticated user can rate movies with his account.
     *
     * @return void
     */
    public function test_Only_Auth_User_Can_Rate_Movies_with_his_account()
    {
        // $this->withExceptionHandling();

        $user1 = create('App\User', ['verified' => User::VERIFIED_USER]);

        $user2 = create('App\User', ['verified' => User::VERIFIED_USER]);

        $movie = create('App\Movie', ['status' => Movie::AVAILABLE_MOVIE]);
        
        $token = $user1->createToken('TestToken', ['rating-movie'])->accessToken;

        $header = [];
        $header['Accept'] = 'application/json';
        $header['Authorization'] = 'Bearer '.$token;

        $data = [
                    "userId"   => $user1->id,
                    "movieId"  => $movie->id,
                    "rating"   => 8,
                ];

        $this->json('POST', 'api/movies/'.$movie->id.'/users/'.$user1->id.'/ratings', 
            ['rating' => 8], $header)
            ->assertStatus(201)
            ->assertJsonFragment($data);

        $data = [
                    "error"   => "This action is unauthorized.",
                    "code" => 403
                ];

        $this->expectException('Illuminate\Auth\Access\AuthorizationException');

        $this->json('POST', 'api/movies/'.$movie->id.'/users/'.$user2->id.'/ratings',
            ['rating' => 8], $header)
            ->assertExactJson($data);   
    }
}
