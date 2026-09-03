<?php

namespace Tests\Feature;

use App\Models\User;
use Filament\Models\Contracts\HasName;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateStudioUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_user_matching_the_studio_schema(): void
    {
        $this->artisan('studio:user:create', [
            '--first_name' => 'Farai',
            '--surname' => 'Rudzi',
            '--email' => 'farai@example.com',
            '--password' => 'SecretPass123',
            '--force_number' => '1234',
            '--phone_number' => '0771234567',
            '--role' => 'ADMIN',
        ])->assertSuccessful();

        $user = User::query()->where('email', 'farai@example.com')->firstOrFail();

        $this->assertSame('Farai', $user->first_name);
        $this->assertSame('Rudzi', $user->surname);
        $this->assertSame('Farai Rudzi', $user->name);
        $this->assertSame('Farai Rudzi', $user->getFilamentName());
        $this->assertInstanceOf(HasName::class, $user);
        $this->assertSame('1234', $user->force_number);
        $this->assertSame('ADMIN', $user->role);
        $this->assertTrue((bool) $user->is_active);
        $this->assertNotNull($user->password);
    }
}
