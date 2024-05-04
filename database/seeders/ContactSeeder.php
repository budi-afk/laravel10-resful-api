<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("username", "admin")->first();

        Contact::create([
            "first_name" => "John",
            "last_name" => "Doe",
            "email" => "john@mail.com",
            "phone" => "1234567890",
            "user_id" => $user->id
        ]);
    }
}
