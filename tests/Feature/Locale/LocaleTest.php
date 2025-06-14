<?php

namespace Tests\Feature\Locale;

use App\Models\Locale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LocaleTest extends TestCase
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
     * Test creating a locale
     */
    public function test_user_can_create_locale(): void
    {
        $localeData = [
            'code' => 'es',
            'name' => 'Spanish',
            'is_active' => true,
            'is_default' => false
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/locales', $localeData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'code',
                    'name',
                    'is_active',
                    'is_default',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('locales', [
            'code' => 'es',
            'name' => 'Spanish',
            'is_active' => true,
            'is_default' => false
        ]);
    }

    /**
     * Test listing locales
     */
    public function test_user_can_list_locales(): void
    {
        Locale::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/locales');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'code',
                        'name',
                        'is_active',
                        'is_default',
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
     * Test updating a locale
     */
    public function test_user_can_update_locale(): void
    {
        $locale = Locale::factory()->create();
        $updateData = [
            'code' => 'fr',
            'name' => 'French',
            'is_active' => true,
            'is_default' => false
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/v1/locales/{$locale->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'code',
                    'name',
                    'is_active',
                    'is_default',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('locales', [
            'id' => $locale->id,
            'code' => 'fr',
            'name' => 'French',
            'is_active' => true,
            'is_default' => false
        ]);
    }

    /**
     * Test deleting a locale
     */
    public function test_user_can_delete_locale(): void
    {
        $locale = Locale::factory()->create([
            'is_default' => false,
            'is_active' => false
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/v1/locales/' . $locale->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message'
            ]);

        $this->assertSoftDeleted('locales', ['id' => $locale->id]);
    }
} 