<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post("/api/contacts", [
            "first_name" => "Eka",
            "last_name" => "Wahid",
            "email" => "eka01@mail.com",
            "phone" => "0812345678",
        ], [
            "Authorization" => "test"
        ])
            ->assertStatus(201)
            ->assertJson([
                "data" => [
                    "first_name" => "Eka",
                    "last_name" => "Wahid",
                    "email" => "eka01@mail.com",
                    "phone" => "0812345678",
                ]
            ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/contacts', [
            'first_name' => '',
            'last_name' => 'zxc',
            'email' => 'wrong',
            'phone' => '03242343243'
        ], [
            'Authorization' => 'test'
        ])->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'first_name' => [
                        'The first name field is required.'
                    ],
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ]);
    }
}
