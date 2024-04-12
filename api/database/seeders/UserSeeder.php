<?php

namespace Database\Seeders;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname' => 'مدیر',
            'lastname' => 'سامانه',
            'username' => 'admin',
            'email' => "admin@gmail.com",
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
            'status' => UserStatus::ADMIN,
        ]);

        $username = 'principle';

        for ($i = 0; $i <= 3; $i++) {
            User::create([
                'firstname' => fake()->firstName(),
                'lastname' => fake()->lastName(),
                'username' => $username,
                'email' => "{$username}@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('12345'),
                'remember_token' => Str::random(10),
                'status' => UserStatus::PRINCIPLE,
            ]);
            $username .= '_' . $i + 1;
        }

        $username = 'teacher';

        for ($i = 0; $i <= 3; $i++) {
            User::create([
                'firstname' => fake()->firstName(),
                'lastname' => fake()->lastName(),
                'username' => $username,
                'email' => "{$username}@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('12345'),
                'remember_token' => Str::random(10),
                'status' => UserStatus::TEACHER,
            ]);
            $username .= '_' . $i + 1;
        }

        $username = 'student';

        for ($i = 0; $i <= 5; $i++) {
            User::create([
                'firstname' => fake()->firstName(),
                'lastname' => fake()->lastName(),
                'username' => $username,
                'email' => "{$username}@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('12345'),
                'remember_token' => Str::random(10),
                'status' => UserStatus::STUDENT,
            ]);
            $username .= '_' . $i + 1;
        }
    }
}
