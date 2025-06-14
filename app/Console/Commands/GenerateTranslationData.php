<?php

namespace App\Console\Commands;

use App\Models\Locale;
use App\Models\Tag;
use App\Models\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateTranslationData extends Command
{
    protected $signature = 'translations:generate {count=150000}';
    protected $description = 'Generate translation data with performance monitoring';

    public function handle()
    {
        $count = $this->argument('count');
        $this->info("Generating {$count} translations...");

        // Create default locales if they don't exist
        $locales = [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'es', 'name' => 'Spanish'],
            ['code' => 'fr', 'name' => 'French'],
        ];
        foreach ($locales as $locale) {
            Locale::firstOrCreate(['code' => $locale['code']], $locale);
        }

        // Create default tags if they don't exist
        $tags = ['web', 'mobile', 'desktop'];
        foreach ($tags as $name) {
            Tag::firstOrCreate(['name' => $name]);
        }

        // Start timing
        $startTime = microtime(true);

        // Use chunking for better memory management
        $chunkSize = 1000;
        $chunks = ceil($count / $chunkSize);

        $bar = $this->output->createProgressBar($chunks);
        $bar->start();

        for ($i = 0; $i < $chunks; $i++) {
            DB::beginTransaction();
            try {
                $translations = [];
                $currentChunkSize = min($chunkSize, $count - ($i * $chunkSize));
                
                for ($j = 0; $j < $currentChunkSize; $j++) {
                    $translations[] = [
                        'locale_id' => Locale::inRandomOrder()->first()->id,
                        'key' => 'key_' . ($i * $chunkSize + $j),
                        'value' => 'value_' . ($i * $chunkSize + $j),
                        'group' => 'general',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                
                Translation::insert($translations);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error generating translations: " . $e->getMessage());
                $this->error("Error in chunk {$i}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        // Calculate and display performance metrics
        $endTime = microtime(true);
        $duration = $endTime - $startTime;
        $rate = $count / $duration;

        $this->info("Generation completed in " . number_format($duration, 2) . " seconds");
        $this->info("Average rate: " . number_format($rate, 2) . " records/second");

        // Verify the data
        $actualCount = Translation::count();
        $this->info("Actual records created: {$actualCount}");

        // Test export performance
        $this->testExportPerformance();
    }

    protected function testExportPerformance()
    {
        $this->info("\nTesting export performance...");
        
        $startTime = microtime(true);
        $translations = Translation::with(['locale', 'tags'])->get();
        $endTime = microtime(true);
        
        $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $this->info("Export test completed in " . number_format($duration, 2) . "ms");
        
        if ($duration > 500) {
            $this->warn("Warning: Export performance is above 500ms threshold");
        }
    }
} 