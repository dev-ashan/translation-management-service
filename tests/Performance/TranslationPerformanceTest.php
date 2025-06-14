<?php

namespace Tests\Performance;

use App\Models\Locale;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationPerformanceTest extends TestCase
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
     * Performance test for listing translations
     *
     * @return void
     */
    public function test_translations_index_performance(): void
    {
        // Seed 1000 translations
        \App\Models\Translation::factory()->count(1000)->create([
            'locale_id' => $this->locale->id,
            'group' => 'performance',
        ]);

        $start = microtime(true);
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/v1/translations');
        $duration = (microtime(true) - $start) * 1000; // ms

        $response->assertStatus(200);
        $this->assertLessThan(500, $duration, 'Translations index endpoint took too long: ' . $duration . 'ms');
    }
} 