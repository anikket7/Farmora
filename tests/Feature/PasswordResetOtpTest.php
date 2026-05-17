<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_request_otp_with_valid_email(): void
    {
        $user = User::factory()->create([
            'email' => 'test@farmora.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'test@farmora.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@farmora.com',
        ]);
    }

    public function test_cannot_reset_password_with_invalid_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'test@farmora.com',
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => 'test@farmora.com',
            'token' => Hash::make('12345'),
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'email' => 'test@farmora.com',
            'token' => '54321', // Incorrect OTP
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('token');
    }

    public function test_cannot_reset_password_with_expired_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'test@farmora.com',
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => 'test@farmora.com',
            'token' => Hash::make('12345'),
            'created_at' => now()->subMinutes(20), // 20 minutes ago (expired)
        ]);

        $response = $this->post(route('password.update'), [
            'email' => 'test@farmora.com',
            'token' => '12345',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('token');
    }

    public function test_can_successfully_reset_password_with_valid_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'test@farmora.com',
            'password' => Hash::make('oldpassword'),
        ]);

        DB::table('password_reset_tokens')->insert([
            'email' => 'test@farmora.com',
            'token' => Hash::make('12345'),
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'email' => 'test@farmora.com',
            'token' => '12345',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // Check if password has been successfully updated
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));

        // Check if token was cleared
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'test@farmora.com',
        ]);
    }
}
