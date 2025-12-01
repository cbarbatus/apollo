<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'setup:admin-role';
    protected $description = 'Assigns the "admin" role to a specified user for testing.';

    public function handle()
    {
        // 1. Ensure the Role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 2. Find the Admin User (REPLACE with your testing email)
        $email = $this->ask('Enter the email of the admin user to assign the role to:');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return Command::FAILURE;
        }

        // 3. Assign the Role
        $user->assignRole($adminRole);
        $this->info("Successfully assigned 'admin' role to user: {$user->email}");
        return Command::SUCCESS;
    }
}