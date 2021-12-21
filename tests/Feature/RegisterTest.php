<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    private $payload;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');

        $this->payload = [
            'name' => 'John Doe',
            'email' => 'example@example.com',
            'password' => 'example_password',
            'password_confirmation' => 'example_password',
        ];
    }

    /**
     * Test user register fields are required.
     *
     * @return void
     */
    public function test_registers_successfully()
    {
        $this->json('POST', 'api/register', $this->payload, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "user" => [
                    "name",
                    "email",
                    "created_at",
                    "updated_at",
                ],
                "token",
            ]);
    }

    /**
     * Test user register fields are required.
     *
     * @return void
     */
    public function test_requires_name_email_password()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertExactJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email field is required."],
                    "name" => ["The name field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    /**
     * Test password confirmation is required.
     *
     * @return void
     */
    public function test_requires_password_confirmation()
    {
        unset($this->payload['password_confirmation']);
        $this->json('POST', 'api/register', $this->payload, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "password" => ["The password confirmation does not match."],
                ]
            ]);
    }

    /**
     * Test password confirmation.
     *
     * @return void
     */
    public function test_password_confirmation()
    {
        $this->payload['password_confirmation'] = 'example_unconfirmed_password';
        $this->json('POST', 'api/register', $this->payload, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "password" => ["The password confirmation does not match."],
                ]
            ]);
    }
}
