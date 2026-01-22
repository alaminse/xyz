<?php

namespace App\Console\Commands;

use App\Models\LectureVideo;
use Illuminate\Console\Command;

class ConvertVideoToHls extends Command
{
    protected $signature = 'video:convert-hls {video_id?} {--all}';
    protected $description = 'Convert lecture videos to HLS format';

    public function handle()
    {
        $videoId = $this->argument('video_id');
        $all = $this->option('all');

        if ($videoId) {
            // Convert specific video
            $videos = LectureVideo::where('id', $videoId)
                ->whereNotNull('uploaded_link')
                ->get();
        } elseif ($all) {
            // Convert all videos
            $videos = LectureVideo::whereNotNull('uploaded_link')->get();
        } else {
            // Convert only unconverted videos
            $videos = LectureVideo::whereNotNull('uploaded_link')
                ->where('is_hls_ready', false)
                ->get();
        }

        if ($videos->isEmpty()) {
            $this->warn('No videos to convert');
            return;
        }

        $this->info("Found {$videos->count()} video(s) to convert");

        foreach ($videos as $video) {
            $this->info("Converting: {$video->title} (ID: {$video->id})");
            $this->convertToHls($video);
        }

        $this->info('✅ Conversion completed!');
    }

    private function convertToHls(LectureVideo $video)
    {
        try {
            // Get source file path
            $relativePath = str_replace('storage/', '', $video->uploaded_link);
            $sourcePath = storage_path('app/public/' . $relativePath);

            if (!file_exists($sourcePath)) {
                $this->error("❌ Source file not found: {$sourcePath}");
                return;
            }

            // Create HLS directory
            $hlsDir = storage_path('app/private/hls/' . $video->id);
            if (!is_dir($hlsDir)) {
                mkdir($hlsDir, 0755, true);
            }

            $outputPath = $hlsDir . '/playlist.m3u8';

            // FFmpeg command for HLS conversion
            $command = sprintf(
                'ffmpeg -i %s -profile:v baseline -level 3.0 -start_number 0 -hls_time 10 -hls_list_size 0 -f hls %s 2>&1',
                escapeshellarg($sourcePath),
                escapeshellarg($outputPath)
            );

            $this->line("Running FFmpeg...");
            exec($command, $output, $returnCode);

            if ($returnCode === 0 && file_exists($outputPath)) {
                // Update database
                $video->update([
                    'hls_path' => 'hls/' . $video->id . '/playlist.m3u8',
                    'is_hls_ready' => true
                ]);

                $this->info("✅ Successfully converted: {$video->title}");
            } else {
                $this->error("❌ Conversion failed: {$video->title}");
                $this->error(implode("\n", $output));
            }

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }
}
