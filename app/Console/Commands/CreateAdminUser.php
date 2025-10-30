<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $this->info('Creating new admin user...');

        $firstName = $this->ask('Enter first name:');
        $lastName = $this->ask('Enter last name:');
        $email = $this->ask('Enter email address:');
        $password = $this->secret('Enter password:');
        $confirmPassword = $this->secret('Confirm password:');

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match!');
            return 1;
        }

        // Get or create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create user
        $user = new User();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        // Assign admin role
        $user->roles()->attach($adminRole->id);

        $this->info('Admin user created successfully!');
        $this->info("Email: {$email}");
        $this->info('You can now login with these credentials.');

        return 0;
    }
}