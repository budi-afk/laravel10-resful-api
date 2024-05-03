<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post("/api/users", [
            "username" => "eka01",
            "password" => "rahasia",
            "name" => "Eka Aja"
        ])
            ->assertStatus(201)
            ->assertJson([
                "data" => [
                    "username" => "eka01",
                    "name" => "Eka Aja"
                ]
            ]);
    }

    public function testRegisterFailed()
    {
        $this->post("/api/users", [
            "username" => "",
            "password" => "",
            "name" => ""
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                    "name" => [
                        "The name field is required."
                    ],

                ]
            ]);
    }

    public function testRegisterDuplicate()
    {
        $this->testRegisterSuccess();

        $this->post("/api/users", [
            "username" => "eka01",
            "password" => "rahasia",
            "name" => "Eka Aja"
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "username already registered"
                    ]

                ]
            ]);
    }

    public function testLoginSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->post("/api/users/login", [
            "username" => "admin",
            "password" => "admin",
        ])
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    "username" => "admin",
                    "name" => "Admin Cuy"

                ]
            ]);

        $user = User::where("username", "admin")->first();
        self::assertNotNull($user);
    }

    public function testLoginNotFound()
    {

        $this->post("/api/users/login", [
            "username" => "admin",
            "password" => "admin",
        ])
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]

                ]
            ]);
    }

    public function testLoginFailed()
    {
        $this->seed(UserSeeder::class);

        $this->post("/api/users/login", [
            "username" => "admin",
            "password" => "lupa",
        ])
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "username or password wrong"
                    ]

                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'username' => 'admin',
                    'name' => 'Admin Cuy'
                ]
            ]);
    }

    public function testGetUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current')
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);

    }

    public function testGetInvalidToken()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'salah'
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'unauthorized'
                    ]
                ]
            ]);

    }
}
