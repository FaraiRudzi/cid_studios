<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateStudioUserCommand extends Command
{
    protected $signature = 'studio:user:create
        {--first_name= : User first name}
        {--surname= : User surname}
        {--email= : User email address}
        {--password= : User password}
        {--force_number= : Force number}
        {--phone_number= : Phone number}
        {--role=PHOTOGRAPHER : User role ADMIN|PHOTOGRAPHER}
        {--is_active=1 : Whether the user is active}';

    protected $description = 'Create a user that matches the studio users schema';

    public function handle(): int
    {
        $firstName = trim((string) $this->option('first_name'));
        $surname = trim((string) $this->option('surname'));
        $email = trim((string) $this->option('email'));
        $password = (string) $this->option('password');

        if ($firstName === '' || $surname === '' || $email === '' || $password === '') {
            $this->error('The following options are required: --first_name, --surname, --email, --password');

            return self::FAILURE;
        }

        $role = strtoupper((string) $this->option('role'));
        if (! in_array($role, ['ADMIN', 'PHOTOGRAPHER'], true)) {
            $this->error('Role must be either ADMIN or PHOTOGRAPHER.');

            return self::FAILURE;
        }

        $user = User::query()->create([
            'force_number' => $this->option('force_number') ?: null,
            'first_name' => $firstName,
            'surname' => $surname,
            'email' => $email,
            'phone_number' => $this->option('phone_number') ?: null,
            'role' => $role,
            'password' => Hash::make($password),
            'is_active' => (bool) $this->option('is_active'),
        ]);

        $this->info("User created successfully: {$user->email}");

        return self::SUCCESS;
    }
}
