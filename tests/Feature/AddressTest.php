<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post(
            '/api/contacts/' . $contact->id . '/addresses',
            [
                'street' => 'test_street',
                'city' => 'test_city',
                'province' => 'test_province',
                'country' => 'test_country',
                'postal_code' => '213123',
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(201)
            ->assertJson([
                'data' => [
                    'street' => 'test_street',
                    'city' => 'test_city',
                    'province' => 'test_province',
                    'country' => 'test_country',
                    'postal_code' => '213123',
                ]
            ]);
    }

    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post(
            '/api/contacts/' . $contact->id . '/addresses',
            [
                'street' => 'test',
                'city' => '',
                'province' => 'test',
                'country' => '',
                'postal_code' => '213123',
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'city' => ['The city field is required.']
                ]
            ]);
    }

    public function testCreateContactNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post(
            '/api/contacts/' . ($contact->id + 1) . '/addresses',
            [
                'street' => 'test',
                'city' => 'test',
                'province' => 'test',
                'country' => 'test',
                'postal_code' => '213123',
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['contact not found']
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get("/api/contacts/$address->contact_id/addresses/$address->id", [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'street' => 'test_street',
                    'city' => 'test_city',
                    'province' => 'test_province',
                    'country' => 'test_country',
                    'postal_code' => '11111'
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->get("/api/contacts/$address->contact_id/addresses/" . ($address->id + 1), [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['address not found']
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put(
            '/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
            [
                'street' => 'update_street',
                'city' => 'update_city',
                'province' => 'update_province',
                'country' => 'update_country',
                'postal_code' => '22222'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'street' => 'update_street',
                    'city' => 'update_city',
                    'province' => 'update_province',
                    'country' => 'update_country',
                    'postal_code' => '22222'
                ]
            ]);
    }

    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put(
            '/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
            [
                'street' => '',
                'city' => 'update_city',
                'province' => 'update_province',
                'country' => 'update_country',
                'postal_code' => '22222'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'street' => ['The street field is required.']
                ]
            ]);
    }

    public function testUpdateNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->put(
            '/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1),
            [
                'street' => 'update',
                'city' => 'update',
                'province' => 'update',
                'country' => 'update',
                'postal_code' => '22222'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['address not found']
                ]
            ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id,
            [
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);

    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);
        $address = Address::query()->limit(1)->first();

        $this->delete('/api/contacts/' . $address->contact_id . '/addresses/' . ($address->id + 1),
            [
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['address not found']
                ]
            ]);
    }
}
