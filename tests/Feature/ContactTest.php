<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
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

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->first();

        $this->get("/api/contacts/$contact->id", [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "first_name" => "John",
                    "last_name" => "Doe",
                    "email" => "john@mail.com",
                    "phone" => "1234567890",
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->first();

        $this->get("/api/contacts/" . ($contact->id + 2), [
            "Authorization" => "test"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "contact not found"
                    ]
                ]
            ]);
    }

    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => 'trial'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'contact not found'
                    ]
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->first();

        $this->put("/api/contacts/$contact->id", [
            "first_name" => "Eka",
            "last_name" => "Wahid",
            "email" => "eka01@mail.com",
            "phone" => "0812345678",
        ], [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "first_name" => "Eka",
                    "last_name" => "Wahid",
                    "email" => "eka01@mail.com",
                    "phone" => "0812345678",
                ]
            ]);
    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->first();

        $this->put("/api/contacts/$contact->id", [
            "first_name" => "",
            "last_name" => "test",
            "email" => "eka01@mail.com",
            "phone" => "",
        ], [
            "Authorization" => "test"
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "first_name" => [
                        "The first name field is required."
                    ],
                    "phone" => [
                        "The phone field is required."
                    ]
                ]
            ]);
    }
}

