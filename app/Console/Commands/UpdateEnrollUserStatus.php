<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EnrollUser;

class UpdateEnrollUserStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enrolluser:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of enrolled users to inactive if end_date is over';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $updated = EnrollUser::where('end_date', '<', now())
            ->where('status', '!=', Status::INACTIVE()) // Assuming Status::INACTIVE() is a constant or method for the inactive status
            ->update(['status' => Status::INACTIVE()]);

        $this->info("Successfully updated {$updated} enrollments to inactive.");
        return Command::SUCCESS;
    }
}
