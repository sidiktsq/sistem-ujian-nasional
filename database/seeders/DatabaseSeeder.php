<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\AdminSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);

        $guru = User::firstOrCreate(
            ['email' => 'guru@ujian.com'],
            [
                'name' => 'Pak Budi (Guru)',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        User::firstOrCreate(
            ['email' => 'murid@ujian.com'],
            [
                'name' => 'Ani (Murid)',
                'password' => Hash::make('password'),
                'role' => 'murid',
            ]
        );
    }
}
