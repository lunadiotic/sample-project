<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function testRequiredFieldsRegistration()
    {
        $this->json('POST', '/api/auth/register', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function testSuccessRegistration()
    {
        $response = $this->json('POST', 'api/auth/register', [
            "name" => "admin",
            "email" => "admin@mail.com",
            "password" => "password",
            "device_name" => "web",
            "is_admin" => false
        ]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'message',
            'data',
            'meta' => [
                'token'
            ]
        ]);
    }
}
