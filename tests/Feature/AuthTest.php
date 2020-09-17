<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use LogicException;
use PHPUnit\Framework\Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use PHPUnit\Framework\InvalidArgumentException as FrameworkInvalidArgumentException;
use Tests\TestCase;
use Throwable;

class AuthTest extends TestCase
{
    /**
     * test every fields are required
     * @return void
     * @throws LogicException
     * @throws BadRequestException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws Throwable
     * @throws FrameworkInvalidArgumentException
     */
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

    /**
     * Test Registration is Success
     * @return void
     * @throws LogicException
     * @throws BadRequestException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws Throwable
     * @throws Exception
     */
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
