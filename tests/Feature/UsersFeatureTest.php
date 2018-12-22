<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersFeatureTest extends TestCase
{
    use DatabaseMigrations;


    public function setUp()
    {
        parent::setUp();

        $this->artisan('passport:install');
    }

    /**
     * Guest can register a new account.
     *
     * @return void
     */
    public function test_Guest_Can_Register()
    {
        $this->withExceptionHandling();

        // $this->withoutMiddleware(\Laravel\Passport\Http\Middleware\CheckClientCredentials::class);

        actingAsClient($this);

        $header = [];

        $input = [
            'name' => 'TestAcount',
            'email'    => 'em1test@test.com',
            'password' => 'secretsecret',
            'password_confirmation' => 'secretsecret'
        ];

        $data = [
                    'userName' => "Testacount",
                    'email' => "em1test@test.com",
                    'isVerified' => 0
                ];

        $this->json('POST', 'api/users', $input)
            ->assertStatus(201)
            ->assertJsonFragment($data);
    }

    /**
     * Users can verify their accounts.
     *
     * @return void
     */
    public function test_Users_Can_Verify()
    {
        $this->withExceptionHandling();

        $user = create('App\User', [
            'verified' => User::UNVERIFIED_USER, 
            'verification_token' => User::generateVerificationCode()
        ]);

        $data = [
                    "data" => "The account has been successfully verified",
                ];

        $response = $this->json('GET', 'api/users/verify/'.$user->verification_token)
            ->assertStatus(200)
            ->assertExactJson($data);

        $user = User::find($user->id);

        $this->assertEquals(User::VERIFIED_USER, $user->verified);
    }
}
