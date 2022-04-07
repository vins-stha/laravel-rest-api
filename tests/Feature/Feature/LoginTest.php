<?php

namespace Tests\Feature\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    public function testEmailAndPasswordRequire()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                "validattion_errors" => [
                    "email" => [
                        "The email field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                ]
            ]);
    }

    public function testForSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email' => 'foobar@foobar.com',
            'password' => bcrypt('foobar')
        ]);

        $payload = [
            'email' => 'foobar@foobar.com',
            'password' => 'foobar'
        ];

        $this->json('POST', 'api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ]
            ]);
    }

    public function testForSuccessfulLogout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $token = JWTAuth::fromUser($user);

        $headers = ['Authorization' => "Bearer $token"];

        // ensure user login
        $this->json('GET', 'api/equipments', $headers)
            ->assertStatus(200);

        // assert logout
        $this->json('POST', 'api/logout', [], $headers)
            ->assertStatus(200)
            ->assertJson([
                    "message" => "Logout Successful"
                ]
            );

        $user = User::find($user->id);

        // confirm user logout
        $this->assertEquals(null, $user->token);
    }
}
