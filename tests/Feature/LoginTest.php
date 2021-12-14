<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    /**
     * Test login requires email and password
     *
     * @return void
     */
    public function test_requires_email_and_password()
    {
        $this->json('POST', 'api/login', ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertExactJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    /**
     * Test logged successfully
     *
     * @return void
     */
    public function test_user_logins_successfuly()
    {
        $user = User::factory()->create([
            'email' => 'testlogin@user.com',
            'password' => bcrypt('example_pass'),
        ]);

        $payload = ['email' => 'testlogin@user.com', 'password' => 'example_pass'];

        $this->json('POST', 'api/login', $payload, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "user" => [
                    "id",
                    "name",
                    "email",
                    "email_verified_at",
                    "created_at",
                    "updated_at",
                ],
                "token",
            ]);
    }

    /**
     * Test logged failed
     *
     * @return void
     */
    public function test_user_logins_failed()
    {
        $user = User::factory()->create([
            'email' => 'testlogin@user.com',
            'password' => bcrypt('example_pass'),
        ]);

        $payload = ['email' => 'testlogin@user.com', 'password' => 'bad_pass'];

        $this->json('POST', 'api/login', $payload, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                "message" => "Bad credentials."
            ]);
    }
}
