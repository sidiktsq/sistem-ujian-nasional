<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create default admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@ujian.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('Email: admin@ujian.com');
        $this->command->info('Password: admin123');
    }
}
