<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile(): void
    {
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->create([
            'role' => 'consumer',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Profile Settings');
    }

    public function test_user_can_update_basic_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'consumer',
            'status' => 'approved',
            'name' => 'Original Name',
            'email' => 'original@farmora.com',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'New Name',
            'email' => 'new@farmora.com',
            'phone' => '1234567890',
            'location' => 'Mumbai',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@farmora.com',
            'phone' => '1234567890',
            'location' => 'Mumbai',
        ]);
    }

    public function test_farmer_can_update_farm_profile_details(): void
    {
        $user = User::factory()->create([
            'role' => 'farmer',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Farmer Name',
            'email' => 'farmer@farmora.com',
            'farm_name' => 'Green Fields Farm',
            'farm_size' => '25 Acres',
            'primary_produce' => 'Organic Tomatoes',
            'bio' => 'Growing healthy organic produce since 2010.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('farmer_profiles', [
            'user_id' => $user->id,
            'farm_name' => 'Green Fields Farm',
            'farm_size' => '25 Acres',
            'primary_produce' => 'Organic Tomatoes',
            'bio' => 'Growing healthy organic produce since 2010.',
        ]);
    }

    public function test_consumer_can_update_delivery_address(): void
    {
        $user = User::factory()->create([
            'role' => 'consumer',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Consumer Name',
            'email' => 'consumer@farmora.com',
            'delivery_address' => '123 Green Avenue, Sector 5, Pune',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('consumer_profiles', [
            'user_id' => $user->id,
            'delivery_address' => '123 Green Avenue, Sector 5, Pune',
        ]);
    }
}
