<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateStudioUserCommand extends Command
{
    protected $signature = 'studio:user:create
        {--first_name= : User first name}
        {--surname= : User surname}
        {--email= : User email address}
        {--password= : DEPRECATED - leave out and you will be prompted (a value here ends up in shell history)}
        {--force_number= : Force number}
        {--phone_number= : Phone number}
        {--role=PHOTOGRAPHER : User role ADMIN|PHOTOGRAPHER}
        {--is_active=1 : Whether the user is active}';

    protected $description = 'Create a user that matches the studio users schema';

    private const MIN_PASSWORD_LENGTH = 12;

    public function handle(): int
    {
        $firstName = trim((string) $this->option('first_name'));
        $surname = trim((string) $this->option('surname'));
        $email = trim((string) $this->option('email'));

        if ($firstName === '' || $surname === '' || $email === '') {
            $this->error('The following options are required: --first_name, --surname, --email');

            return self::FAILURE;
        }

        if (User::query()->where('email', $email)->exists()) {
            $this->error("A user with the email {$email} already exists.");

            return self::FAILURE;
        }

        $role = strtoupper((string) $this->option('role'));
        if (! in_array($role, ['ADMIN', 'PHOTOGRAPHER'], true)) {
            $this->error('Role must be either ADMIN or PHOTOGRAPHER.');

            return self::FAILURE;
        }

        $password = (string) $this->option('password');

        if ($password !== '') {
            $this->warn('Passing --password on the command line is insecure: it is stored in shell history. Prefer the prompt.');
        } else {
            if (! $this->input->isInteractive()) {
                $this->error('No password supplied and this is a non-interactive session.');

                return self::FAILURE;
            }

            $password = (string) $this->secret('Password');

            if ($password !== (string) $this->secret('Confirm password')) {
                $this->error('The passwords do not match.');

                return self::FAILURE;
            }
        }

        if (mb_strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $this->error('The password must be at least '.self::MIN_PASSWORD_LENGTH.' characters.');

            return self::FAILURE;
        }

        // The model's "hashed" cast hashes the password.
        $user = User::query()->create([
            'force_number' => $this->option('force_number') ?: null,
            'first_name' => $firstName,
            'surname' => $surname,
            'email' => $email,
            'phone_number' => $this->option('phone_number') ?: null,
            'role' => $role,
            'password' => $password,
            'is_active' => filter_var($this->option('is_active'), FILTER_VALIDATE_BOOLEAN),
        ]);

        $this->info("User created successfully: {$user->email}");

        return self::SUCCESS;
    }
}
