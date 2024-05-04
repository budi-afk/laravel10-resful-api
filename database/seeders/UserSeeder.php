<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "username" => "admin",
            "password" => Hash::make("admin"),
            "name" => "Admin Cuy",
            "token" => "test"
        ]);

        User::create([
            "username" => "manager",
            "password" => Hash::make("manager"),
            "name" => "Manager Cuy",
            "token" => "trial"
        ]);
    }
}
