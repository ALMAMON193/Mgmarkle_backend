<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'user_type' => 'admin',
            'is_verified' => true,
            'email_verified_at' => now(),
            'verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 10 User
        for ($i = 1; $i <= 5; $i++) {
            DB::table('users')->insert([
                'name' => "User $i",
                'email' => "seeker$i@gmail.com",
                'password' => Hash::make('12345678'),
                'user_type' => 'seeker',
                'is_verified' => true,
                'email_verified_at' => now(),
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        for ($i = 1; $i <= 5; $i++) {
            DB::table('users')->insert([
                'name' => "Spiritual Guide $i",
                'email' => "spiritual_guide$i@gmail.com",
                'password' => Hash::make('12345678'),
                'user_type' => 'spiritual_guide',
                'is_verified' => true,
                'email_verified_at' => now(),
                'verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
