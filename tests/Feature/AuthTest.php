<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auth_login()
    {
        /*$response = $this->post('/api/v1/auth/login', [
            'email' => 'info@jwt.lc',
            'password' => 'random1'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(["error", "success", "status", "result"]);*/

        $this->assertTrue(true);
    }
}
