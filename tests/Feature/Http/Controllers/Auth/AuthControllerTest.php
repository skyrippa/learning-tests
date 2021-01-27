<?php


namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

//    public function setUp()
//    {
//        parent::setUp();
//
//        $this->artisan('passport:install');
//    }

    /**
     * @test
     */
    public function can_authenticate()
    {
        $user = $this->create('App\Models\User', 'App\Http\Resources\User', [], false);

        $response = $this->json('POST', '/auth/token', [
           'email' => $user->email,
           'password' => 'secret'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }
}
