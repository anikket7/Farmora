<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuspensionTest extends TestCase
{
    use RefreshDatabase;

    public function test_suspended_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'suspended@example.com',
            'password' => bcrypt('password123'),
            'status' => 'suspended',
            'role' => 'farmer',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'suspended@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_logged_in_user_is_logged_out_if_suspended(): void
    {
        $user = User::factory()->create([
            'email' => 'active@example.com',
            'password' => bcrypt('password123'),
            'status' => 'approved',
            'role' => 'farmer',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        // Access dashboard successfully
        $response = $this->get(route('farmer.dashboard'));
        $response->assertStatus(200);

        // Suspend user
        $user->update(['status' => 'suspended']);

        // Next request should automatically log them out and redirect to login
        $response2 = $this->get(route('farmer.dashboard'));
        $response2->assertRedirect(route('login'));
        $response2->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
