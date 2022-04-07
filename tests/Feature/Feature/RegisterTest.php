<?php

namespace Tests\Feature\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions ;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions ;


    public function testEmailAndPasswordRequire()
    {
        $this->json('POST','api/register')
            ->assertStatus(400)
            ->assertJson([
                "validation_errors" => [
                    "name" => [
                        "The name field is required."
                    ],
                    "email" => [
                        "The email field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                    "re-password" => [
                        "The re-password field is required."
                    ]
                ]
            ]);
    }

    public function testForSuccessfulRegister()
    {
        $payload = [
            'name'=>'foobar',
            'email'=>'foobar@foobar.com',
            'password'=>'foobar',
            're-password'=>'foobar'
        ];

        $this->json('POST', 'api/register', $payload)
            ->assertStatus(201)
            ->assertJsonStructure([
                    'message',
                    'user' => [
                        'name',
                        'email',
                        'updated_at',
                        'created_at',
                        'id'
                    ]                ,
            ]);
    }
}
