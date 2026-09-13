<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NoteSampleExport;

class GenerateNoteSample extends Command
{
    protected $signature = 'note:generate-sample';
    protected $description = 'Generate the Note bulk-upload sample Excel template';

    public function handle()
    {
        if (!file_exists(storage_path('app/templates'))) {
            mkdir(storage_path('app/templates'), 0755, true);
        }

        Excel::store(new NoteSampleExport, 'templates/note_sample.xlsx', 'local');

        $this->info('Sample template generated at storage/app/templates/note_sample.xlsx');
    }
}
