<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_page(): void
    {
        $this->get(route('login'))
            ->assertOk();
    }

    public function test_guest_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('bukutamu.kunjungan.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_protected_routes(): void
    {
        $this->get(route('bukutamu.kunjungan.index'))
            ->assertRedirect(route('login'));
    }

    public function test_login_is_rate_limited(): void
    {
        // routes/web.php applies throttle:5,1 on POST /login
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post(route('login.store'), [
                'email' => 'nobody@example.com',
                'password' => 'wrong',
            ]);
        }

        $response->assertStatus(429);
    }
}
