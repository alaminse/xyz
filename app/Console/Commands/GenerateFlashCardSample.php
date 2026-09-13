<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FlashCardSampleExport;

class GenerateFlashCardSample extends Command
{
    protected $signature = 'flashcard:generate-sample';
    protected $description = 'Generate the Flash Card bulk-upload sample Excel template';

    public function handle()
    {
        if (!file_exists(storage_path('app/templates'))) {
            mkdir(storage_path('app/templates'), 0755, true);
        }

        Excel::store(new FlashCardSampleExport, 'templates/flashcard_sample.xlsx', 'local');

        $this->info('Sample template generated at storage/app/templates/flashcard_sample.xlsx');
    }
}
