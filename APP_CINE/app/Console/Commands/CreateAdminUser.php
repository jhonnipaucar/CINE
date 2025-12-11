<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-admin';
    protected $description = 'Create admin user';

    public function handle()
    {
        try {
            $user = User::where('email', 'admin@cine.com')->first();
            if ($user) {
                $this->info('Admin user already exists');
                return 0;
            }

            User::create([
                'name' => 'Admin',
                'email' => 'admin@cine.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]);

            $this->info('Admin user created successfully');
            return 0;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
