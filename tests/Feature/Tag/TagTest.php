<?php

namespace Tests\Feature\Tag;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->token = $user->createToken('test-token')->plainTextToken;
    }

    /**
     * Test listing tags
     */
    public function test_user_can_list_tags(): void
    {
        Tag::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/tags');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total'
                ]
            ]);
    }

    /**
     * Test creating a tag
     */
    public function test_user_can_create_tag(): void
    {
        $tagData = [
            'name' => 'Test Tag'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/tags', $tagData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('tags', $tagData);
    }

    /**
     * Test updating a tag
     */
    public function test_user_can_update_tag(): void
    {
        $tag = Tag::factory()->create();
        $updateData = [
            'name' => 'Updated Tag'
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/v1/tags/' . $tag->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => 'Updated Tag'
        ]);
    }

    /**
     * Test deleting a tag
     */
    public function test_user_can_delete_tag(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/v1/tags/' . $tag->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message'
            ]);

        $this->assertSoftDeleted('tags', ['id' => $tag->id]);
    }
} 