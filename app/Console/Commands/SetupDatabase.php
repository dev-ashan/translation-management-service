<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SetupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the database for the Translation Management Service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up the database...');

        // Create database if it doesn't exist
        $database = config('database.connections.mysql.database');
        $this->info("Creating database '{$database}' if it doesn't exist...");
        
        try {
            DB::statement("CREATE DATABASE IF NOT EXISTS {$database}");
            $this->info("Database '{$database}' created or already exists.");
        } catch (\Exception $e) {
            $this->error("Failed to create database: " . $e->getMessage());
            return 1;
        }

        // Run migrations
        $this->info('Running migrations...');
        try {
            Artisan::call('migrate:fresh');
            $this->info('Migrations completed successfully.');
        } catch (\Exception $e) {
            $this->error("Failed to run migrations: " . $e->getMessage());
            return 1;
        }

        // Run seeders
        $this->info('Seeding the database...');
        try {
            Artisan::call('db:seed');
            $this->info('Database seeded successfully.');
        } catch (\Exception $e) {
            $this->error("Failed to seed database: " . $e->getMessage());
            return 1;
        }

        $this->info('Database setup completed successfully!');
        return 0;
    }
} 