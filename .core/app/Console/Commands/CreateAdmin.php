<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {email : Login e-mail address} {--name=Admin : Display name}';

    protected $description = 'Create an admin user, or reset the password of an existing one';

    public function handle()
    {
        $email = $this->argument('email');

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('That is not a valid e-mail address.');

            return self::FAILURE;
        }

        // Typed hidden, so the password never lands in your shell history.
        $password = (string) $this->secret('Password (at least 12 characters)');
        $repeat   = (string) $this->secret('Repeat password');

        if ($password !== $repeat) {
            $this->error('The passwords do not match.');

            return self::FAILURE;
        }

        if (strlen($password) < 12) {
            $this->error('Use at least 12 characters.');

            return self::FAILURE;
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => $this->option('name'), 'password' => $password]
        );

        $this->info("Admin saved: {$email}");

        return self::SUCCESS;
    }
}
