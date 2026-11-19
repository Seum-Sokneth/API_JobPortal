<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\SeekersProfile;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_registration_as_seeker_creates_a_seeker_profile(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Avery Applicant',
            'email' => 'avery@example.com',
            'role' => 'seeker',
            'password' => 'StrongPass123',
            'confirm_password' => 'StrongPass123',
        ]);

        $response->assertCreated()
            ->assertJsonPath('seeker_profile.fullname', 'Avery Applicant')
            ->assertJsonPath('seeker_profile.email', 'avery@example.com')
            ->assertJsonPath('seeker_profile.phone', '')
            ->assertJsonMissingPath('seeker_profile.password');

        $userId = $response->json('data.id');

        $this->assertDatabaseHas('seekers_profiles', [
            'user_id' => $userId,
            'fullname' => 'Avery Applicant',
            'email' => 'avery@example.com',
            'phone' => '',
        ]);

        $seekerProfile = SeekersProfile::query()
            ->where('user_id', $userId)
            ->firstOrFail();

        $this->assertTrue(Hash::check('StrongPass123', $seekerProfile->password));
    }
}
