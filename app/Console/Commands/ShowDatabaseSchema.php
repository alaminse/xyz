<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ShowDatabaseSchema extends Command
{
    // php artisan db:schema
    protected $signature = 'db:schema';
    protected $description = 'Show all tables and their columns';

    public function handle()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $key = "Tables_in_{$dbName}";

        foreach ($tables as $table) {
            $tableName = $table->$key;
            $this->info("\n📋 Table: {$tableName}");
            $this->line(str_repeat('=', 60));

            $columns = DB::select("SHOW COLUMNS FROM `{$tableName}`");

            foreach ($columns as $column) {
                $this->line("  • {$column->Field} ({$column->Type})");
            }
        }

        return 0;
    }
}
