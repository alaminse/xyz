<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\McqSampleExport;

class GenerateMcqSample extends Command
{
    protected $signature = 'mcq:generate-sample';
    protected $description = 'Generate the MCQ bulk-upload sample Excel template';

    public function handle()
    {
        if (!file_exists(storage_path('app/templates'))) {
            mkdir(storage_path('app/templates'), 0755, true);
        }

        Excel::store(new McqSampleExport, 'templates/mcq_sample.xlsx', 'local');

        $this->info('Sample template generated at storage/app/templates/mcq_sample.xlsx');
    }
}
