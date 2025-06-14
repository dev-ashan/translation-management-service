<?php

namespace Database\Factories;

use App\Models\Locale;
use App\Models\Translation;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure we have at least one locale
        $locale = Locale::first() ?? Locale::factory()->create();

        // Generate a unique key
        $key = 'key_' . $this->faker->unique()->word();

        // Generate a random value
        $value = $this->faker->sentence();

        // Define available groups
        $groups = ['general', 'auth', 'validation', 'messages'];

        return [
            'locale_id' => $locale->id,
            'key' => $key,
            'value' => $value,
            'group' => $this->faker->randomElement($groups),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Translation $translation) {
            // Attach 1-3 random tags to each translation
            $tags = Tag::inRandomOrder()->take($this->faker->numberBetween(1, 3))->get();
            $translation->tags()->attach($tags);
        });
    }
} 