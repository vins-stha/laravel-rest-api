<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\User;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EquipmentTest extends TestCase
{
    use DatabaseTransactions;


    public function test_successful_equipment_create()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $token = JWTAuth::fromUser($user);

        $headers = ['Authorization' => "Bearer $token"];

        $payload = [
            "name" => "Lorem",
            "quantity" => 10,
            "internal_notes" => "ipsum"
        ];

        $this->json('POST', '/api/equipments', $payload, $headers)
            ->assertStatus(200);
    }

    public function test_successful_get_equipment_by_registered_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $token = JWTAuth::fromUser($user);

        $headers = ['Authorization' => "Bearer $token"];

        $equipment = Equipment::factory()->create();

        $response = $this->json(
            'GET',
            '/api/equipments/' . $equipment->id,
            $headers
        )
            ->assertStatus(200)
            ->assertJson([
                'message' => "200",
                'equipments' => [
                    'id' => $equipment->id,
                    'name' =>$equipment->name,
                    'quantity' => $equipment->quantity,
                    'internal_notes' => $equipment->internal_notes
                ],
            ]);
    }

    public function test_successful_update_equipment()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $token = JWTAuth::fromUser($user);

        $headers = ['Authorization' => "Bearer $token"];

        $equipment = Equipment::factory()->create([
            "name" => "Lorem",
            "quantity" => 10,
            "internal_notes" => "ipsum"
        ]);

        $payload = [
            "name" => "New lorem",
            "quantity" => 110,
            "internal_notes" => "New ipsum"
        ];

        $response = $this->json(
            'PUT',
            '/api/equipments/' . $equipment->id,
            $payload,
            $headers
        )
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => 1,
                    'name' => 'New lorem',
                    'quantity' => 110,
                    'internal_notes' => 'New ipsum'
                ],
                'status' => 200

            ]);
    }

    public function test_successful_deletion_equipment_by_registered_user()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $token = JWTAuth::fromUser($user);

        $headers = ['Authorization' => "Bearer $token"];

        $equipment = Equipment::factory()->create([
            "name" => "Lorem",
            "quantity" => 10,
            "internal_notes" => "Ipsum"
        ]);

        $response = $this->json(
            'DELETE',
            '/api/equipments/' . $equipment->id,
            $headers
        )
            ->assertStatus(200)
            ->assertJson([
                'message' => "Deleted successfully",
            ]);
    }

    public function test_successful_retrieve_by_guest()
    {
        $equipment = Equipment::factory()->create();

        $response = $this->json(
            'GET',
            '/api/find-equipments/' . $equipment->id

        )
            ->assertStatus(200)
            ->assertJson([
                    "data" => [
                        "id" => $equipment->id,
                        "name" => $equipment->name,
                        "quantity" => $equipment->quantity
                    ]
                ]
            );
    }

}
