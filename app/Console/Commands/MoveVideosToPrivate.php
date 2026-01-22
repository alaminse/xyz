<?php

namespace App\Console\Commands;

use App\Models\LectureVideo;
use Illuminate\Console\Command;

class MoveVideosToPrivate extends Command
{
    protected $signature = 'video:move-private';
    protected $description = 'Move lecture videos from public to private storage';

    public function handle()
    {
        $videos = LectureVideo::whereNotNull('uploaded_link')->get();

        if ($videos->isEmpty()) {
            $this->warn('No videos found');
            return;
        }

        $privateDir = storage_path('app/private/videos');

        // Create private directory if not exists
        if (!is_dir($privateDir)) {
            mkdir($privateDir, 0755, true);
            $this->info("Created private directory: {$privateDir}");
        }

        $moved = 0;
        $failed = 0;

        foreach ($videos as $video) {
            $relativePath = str_replace('storage/', '', $video->uploaded_link);
            $sourcePath = storage_path('app/public/' . $relativePath);

            if (file_exists($sourcePath)) {
                $filename = basename($sourcePath);
                $destPath = $privateDir . '/' . $filename;

                // Skip if already exists
                if (file_exists($destPath)) {
                    $this->line("⏭️  Already exists: {$video->title}");
                    continue;
                }

                if (copy($sourcePath, $destPath)) {
                    $this->info("✅ Moved: {$video->title}");
                    $moved++;
                } else {
                    $this->error("❌ Failed to move: {$video->title}");
                    $failed++;
                }
            } else {
                $this->warn("⚠️  Source not found: {$video->title}");
                $failed++;
            }
        }

        $this->info("
Migration completed!");
        $this->info("Moved: {$moved} | Failed: {$failed}");
    }
}

