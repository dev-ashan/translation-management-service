<?php

namespace Tests\Feature\Translation;

use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    use RefreshDatabase;

    protected string $token;
    protected Locale $locale;
    protected Tag $tag;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->token = $user->createToken('test-token')->plainTextToken;
        $this->locale = Locale::factory()->create();
        $this->tag = Tag::factory()->create();
    }

    /**
     * Test creating a translation
     */
    public function test_user_can_create_translation(): void
    {
        $translationData = [
            'locale_id' => $this->locale->id,
            'key' => 'test.key',
            'value' => 'Test Value',
            'group' => 'general',
            'tags' => [$this->tag->id]
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/v1/translations', $translationData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'locale',
                    'key',
                    'value',
                    'group',
                    'tags',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('translations', [
            'locale_id' => $this->locale->id,
            'key' => 'test.key',
            'value' => 'Test Value',
            'group' => 'general'
        ]);
    }

    /**
     * Test listing translations
     */
    public function test_user_can_list_translations(): void
    {
        Translation::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/translations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'locale',
                        'key',
                        'value',
                        'group',
                        'tags',
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
     * Test updating a translation
     */
    public function test_user_can_update_translation(): void
    {
        $translation = Translation::factory()->create([
            'group' => 'messages'
        ]);
        $updateData = [
            'locale_id' => $this->locale->id,
            'key' => 'updated.key',
            'value' => 'Updated Value',
            'group' => 'general',
            'tags' => [$this->tag->id]
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson("/api/v1/translations/{$translation->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'locale',
                    'key',
                    'value',
                    'group',
                    'tags',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('translations', [
            'id' => $translation->id,
            'locale_id' => $this->locale->id,
            'key' => 'updated.key',
            'value' => 'Updated Value',
            'group' => 'general'
        ]);
    }

    /**
     * Test deleting a translation
     */
    public function test_user_can_delete_translation(): void
    {
        $translation = Translation::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson("/api/v1/translations/{$translation->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message'
            ]);

        $this->assertSoftDeleted('translations', ['id' => $translation->id]);
    }

    /**
     * Test searching translations
     */
    public function test_user_can_search_translations(): void
    {
        Translation::factory()->create([
            'key' => 'searchable.key',
            'value' => 'Searchable Value'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/translations/search?q=searchable');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'locale',
                        'key',
                        'value',
                        'group',
                        'tags',
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
     * Test filtering translations by locale
     */
    public function test_user_can_filter_translations_by_locale(): void
    {
        Translation::factory()->create(['locale_id' => $this->locale->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("/api/v1/translations?locale={$this->locale->code}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'locale',
                        'key',
                        'value',
                        'group',
                        'tags',
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
     * Test filtering translations by tags
     */
    public function test_user_can_filter_translations_by_tags(): void
    {
        $translation = Translation::factory()->create();
        $translation->tags()->sync([$this->tag->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson("/api/v1/translations?tags={$this->tag->name}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'locale',
                        'key',
                        'value',
                        'group',
                        'tags',
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
} 