<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SbaSampleExport;

class GenerateSbaSample extends Command
{
    protected $signature = 'sba:generate-sample';
    protected $description = 'Generate the SBA bulk-upload sample Excel template';

    public function handle()
    {
        if (!file_exists(storage_path('app/templates'))) {
            mkdir(storage_path('app/templates'), 0755, true);
        }

        Excel::store(new SbaSampleExport, 'templates/sba_sample.xlsx', 'local');

        $this->info('Sample template generated at storage/app/templates/sba_sample.xlsx');
    }
}
