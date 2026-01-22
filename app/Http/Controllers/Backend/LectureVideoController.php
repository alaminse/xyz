<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Status;
use App\Models\Course;
use App\Traits\Upload;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\Models\LectureVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LectureVideoController extends Controller
{
    use Upload;

    public function index()
    {
        $courses = courseByModule('videos');
        return view('backend.lecture-video.index', compact('courses'));
    }

    public function create()
    {
        $courses = courseByModule('videos');
        return view('backend.lecture-video.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_ids'        => 'required|array',
            'course_ids.*'      => 'exists:courses,id',
            'chapter_id'        => 'required|exists:chapters,id',
            'lesson_id'         => 'required|exists:lessons,id',
            'title'             => 'required|string|max:255',
            'upload_method'     => 'required|in:youtube,bunny',
            'youtube_link'      => 'required_if:upload_method,youtube|nullable|url',
            'uploaded_link'     => 'required_if:upload_method,bunny|nullable|string',
            'isPaid'            => 'nullable|boolean',
        ]);


        if (contentExists(\App\Models\LectureVideo::class, $data['chapter_id'], $data['lesson_id'], $data['course_ids'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lecture Video already exists for this combination.');
        }

        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        $data['slug']   = Str::slug($data['title']);
        $data['isPaid'] = $request->boolean('isPaid');
        $data['status'] = 1;
        $data['hls_path'] = null;
        $data['is_hls_ready'] = 0;

        // Upload method mapping
        if ($data['upload_method'] === 'youtube') {
            // 🎬 YouTube: Convert to embed URL
            $data['youtube_link'] = $this->convertToEmbedUrl($data['youtube_link']);
            $data['uploaded_link'] = null;
        } else {
            // 🐰 Bunny: Set security and store video ID
            $data['youtube_link'] = null;
            $data['uploaded_link'] = $data['uploaded_link']; // Store in uploaded_link

            // Set Bunny security settings
            $this->setBunnySecuritySettings($data['uploaded_link']);
        }

        unset($data['upload_method']);

        DB::beginTransaction();

        try {
            // 🎥 Create lecture video
            $lectureVideo = LectureVideo::create($data);

            // 🔗 Attach courses
            $lectureVideo->courses()->syncWithoutDetaching($courseIds);

            DB::commit();

            return redirect()
                ->route('admin.lecturevideos.index')
                ->with('success', 'Lecture Video created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('LectureVideo Store Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', 'Failed to create video: ' . $e->getMessage());
        }
    }

    // 🎯 Convert YouTube URL to embed format
    private function convertToEmbedUrl($url)
    {
        // Already embed URL
        if (strpos($url, 'youtube.com/embed/') !== false) {
            return $url;
        }

        $videoId = null;

        // Format 1: https://www.youtube.com/watch?v=VIDEO_ID
        if (preg_match('/[?&]v=([^&]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Format 2: https://youtu.be/VIDEO_ID
        elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Format 3: https://www.youtube.com/embed/VIDEO_ID
        elseif (preg_match('/youtube\.com\/embed\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }

        return $videoId ? "https://www.youtube.com/embed/{$videoId}" : $url;
    }

    // 🔐 Set Bunny.net security settings
    private function setBunnySecuritySettings($videoId)
    {
        try {
            $libraryId = env('BUNNY_LIBRARY_ID');
            $apiKey = env('BUNNY_API_KEY');

            if (!$libraryId || !$apiKey) {
                Log::warning('Bunny credentials not configured');
                return;
            }

            $client = new \GuzzleHttp\Client();

            $client->post("https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}", [
                'headers' => [
                    'AccessKey' => $apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'enableMP4Fallback' => false,
                    'enableTokenAuthentication' => true,
                    'enableTokenIPVerification' => true,
                    'allowedReferrers' => [
                        parse_url(config('app.url'), PHP_URL_HOST)
                    ],
                ]
            ]);

            Log::info("Bunny security set for video: {$videoId}");

        } catch (\Exception $e) {
            Log::error('Bunny security settings failed: ' . $e->getMessage());
        }
    }

    // 🔥 Optimized Bunny Upload Method
    public function uploadToBunny(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        try {
            $chunk = $request->input('chunk', 0);
            $totalChunks = $request->input('totalChunks', 1);
            $fileName = $request->input('fileName');
            $title = $request->input('title', $fileName);

            $tempDir = storage_path('app/temp/bunny_chunks');

            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $fileHash = md5($fileName . auth()->id() . time());
            $chunkFileName = $fileHash . '_chunk_' . $chunk;
            $tempFile = $tempDir . '/' . $chunkFileName;

            // Save chunk data
            $chunkData = file_get_contents('php://input');

            if (empty($chunkData)) {
                throw new \Exception('No chunk data received');
            }

            file_put_contents($tempFile, $chunkData);
            Log::info("Chunk {$chunk}/{$totalChunks} saved: " . strlen($chunkData) . " bytes");

            // Last chunk - merge and upload to Bunny
            if ($chunk == $totalChunks - 1) {
                Log::info("Merging {$totalChunks} chunks...");

                $finalDir = storage_path('app/temp');
                if (!is_dir($finalDir)) {
                    mkdir($finalDir, 0755, true);
                }

                $finalFile = $finalDir . '/' . $fileHash . '_' . $fileName;
                $output = fopen($finalFile, 'wb');

                if (!$output) {
                    throw new \Exception('Could not create final file');
                }

                // Merge all chunks
                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkPath = $tempDir . '/' . $fileHash . '_chunk_' . $i;

                    if (!file_exists($chunkPath)) {
                        fclose($output);
                        throw new \Exception("Chunk {$i} not found");
                    }

                    $input = fopen($chunkPath, 'rb');
                    if ($input) {
                        while (!feof($input)) {
                            fwrite($output, fread($input, 8192));
                        }
                        fclose($input);
                        unlink($chunkPath);
                    }
                }
                fclose($output);

                $finalSize = filesize($finalFile);
                Log::info("Final file: " . ($finalSize / (1024 * 1024)) . " MB");

                // Upload to Bunny.net
                $bunnyVideoId = $this->uploadToBunnyNet($finalFile, $title);

                // Cleanup
                if (file_exists($finalFile)) {
                    unlink($finalFile);
                }

                if (is_dir($tempDir) && count(scandir($tempDir)) == 2) {
                    rmdir($tempDir);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Upload complete',
                    'uploaded_link' => $bunnyVideoId
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Chunk {$chunk} uploaded"
            ]);

        } catch (\Exception $e) {
            Log::error('Bunny Upload Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show(LectureVideo $video)
    {
        return view('backend.lecture-video.show', compact('video'));
    }

    public function edit(LectureVideo $video)
    {
        $courses = courseByModule('videos');
        return view('backend.lecture-video.edit', compact('video', 'courses'));
    }

    public function update(Request $request, LectureVideo $video)
    {
        $data = $request->validate([
            'course_ids'    => 'required|array',
            'course_ids.*'  => 'exists:courses,id',
            'chapter_id'    => 'required|exists:chapters,id',
            'lesson_id'     => 'required|exists:lessons,id',
            'title'         => 'required|string|max:255',
            'youtube_link'  => 'nullable|url',
            'uploaded_link' => 'nullable|string', // Bunny video ID from upload
            'status'        => 'required|in:1,2',
            'isPaid'        => 'nullable|boolean',
        ]);

        if (contentExists(\App\Models\LectureVideo::class, $data['chapter_id'], $data['lesson_id'], $data['course_ids'], $video->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lecture Video already exists for this combination.');
        }

        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        $data['slug'] = Str::slug($data['title']);
        $data['isPaid'] = $request->boolean('isPaid');

        // Handle video source change
        if (!empty($data['youtube_link'])) {
            // YouTube video selected
            $data['youtube_link'] = $this->convertToEmbedUrl($data['youtube_link']);

            // Delete old Bunny video if exists
            if ($video->uploaded_link) {
                $this->deleteBunnyVideo($video->uploaded_link);
            }
            $data['uploaded_link'] = null;

        } elseif (!empty($data['uploaded_link'])) {
            // New Bunny video uploaded

            // Delete old Bunny video if exists$data['youtube_link'] = null;

            // Set security for new Bunny video
            $this->setBunnySecuritySettings($data['uploaded_link']);
        } else {
            // Keep existing video (no change)
            unset($data['youtube_link'], $data['uploaded_link']);
        }

        DB::beginTransaction();

        try {
            $video->update($data);
            $video->courses()->sync($courseIds);

            DB::commit();

            return redirect()
                ->route('admin.lecturevideos.show', $video->id)
                ->with('success', 'Video updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Video Update Failed', [
                'error' => $e->getMessage()
            ]);

            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy(LectureVideo $video)
    {
        // Delete uploaded file
        if ($video->uploaded_link != null) {
            $fullPath = public_path($video->uploaded_link);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            // Delete from private storage
            $privateFile = storage_path('app/private/videos/' . basename($video->uploaded_link));
            if (file_exists($privateFile)) {
                unlink($privateFile);
            }
        }

        // Delete HLS files
        $hlsDir = storage_path('app/private/hls/' . $video->id);
        if (is_dir($hlsDir)) {
            array_map('unlink', glob("$hlsDir/*.*"));
            rmdir($hlsDir);
        }

        // Delete from Bunny.net if exists
        if ($video->uploaded_link) {
            $this->deleteBunnyVideo($video->uploaded_link);
        }

        $delete = $video->delete();

        if (!$delete)
            return redirect()->route('admin.lecturevideos.index')->with('error', 'Delete failed! Try again!');

        return redirect()->back()->with('success', 'Video deleted successfully');
    }

    public function status(LectureVideo $video)
    {
        $update = $video->update(['status' => $video->status == 1 ? 2 : 1]);

        if (!$update)
            return redirect()->back()->with('error', 'Status update failed! Try again!');

        return redirect()->back()->with('success', 'Status updated successfully');
    }


    private function uploadToBunnyNet($filePath, $title)
    {
        // Bunny.net API credentials
        $libraryId = env('BUNNY_LIBRARY_ID');
        $apiKey = env('BUNNY_API_KEY');

        if (!$libraryId || !$apiKey) {
            throw new \Exception('Bunny.net credentials not configured in .env file');
        }

        if (!file_exists($filePath)) {
            throw new \Exception('Video file not found: ' . $filePath);
        }

        // Step 1: Create video in Bunny.net
        $createUrl = "https://video.bunnycdn.com/library/{$libraryId}/videos";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $createUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'AccessKey: ' . $apiKey,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['title' => $title]));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($httpCode != 200 && $httpCode != 201) {
            throw new \Exception('Failed to create video in Bunny.net. HTTP Code: ' . $httpCode . ' Response: ' . $response);
        }

        $videoData = json_decode($response, true);

        if (!isset($videoData['guid'])) {
            throw new \Exception('Invalid response from Bunny.net: ' . $response);
        }

        $videoId = $videoData['guid'];

        // Step 2: Upload video file
        $uploadUrl = "https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uploadUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'AccessKey: ' . $apiKey,
            'Content-Type: application/octet-stream'
        ]);

        $fileHandle = fopen($filePath, 'rb');
        if (!$fileHandle) {
            throw new \Exception('Could not open file for upload');
        }

        curl_setopt($ch, CURLOPT_INFILE, $fileHandle);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($filePath));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fileHandle);

        if ($httpCode != 200 && $httpCode != 201) {
            throw new \Exception('Failed to upload video to Bunny.net. HTTP Code: ' . $httpCode . ' Response: ' . $response);
        }

        return $videoId;
    }

    /**
     * Delete video from Bunny.net
     */
    private function deleteBunnyVideo($videoId)
    {
        try {
            $libraryId = env('BUNNY_LIBRARY_ID');
            $apiKey = env('BUNNY_API_KEY');

            if (!$libraryId || !$apiKey) return;

            $client = new Client();
            $client->delete("https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}", [
                'headers' => [
                    'AccessKey' => $apiKey,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Bunny.net delete failed: ' . $e->getMessage());
        }
    }

    /**
     * 🔍 Get video status from Bunny.net
     */
    public function getBunnyVideoStatus($videoId)
    {
        try {
            $libraryId = env('BUNNY_LIBRARY_ID');
            $apiKey = env('BUNNY_API_KEY');

            $client = new Client();
            $response = $client->get("https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}", [
                'headers' => [
                    'AccessKey' => $apiKey,
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json([
                'status' => $data['status'], // 0=Created, 1=Uploading, 2=Processing, 3=Encoding, 4=Finished
                'encoding_progress' => $data['encodeProgress'],
                'thumbnail_url' => $data['thumbnailFileName'] ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getData()
    {
        $slug = request('slug');

        if ($slug == null) {
            return response()->json(['msg' => 'Course not selected!!']);
        }

        $course = Course::select('id', 'slug')->where('slug', $slug)->first();

        if ($course == null) {
            return response()->json(['html' => '']);
        }

        $videos = LectureVideo::whereHas('courses', fn($q)=> $q->where('course_id', $course->id))
                ->with(['chapter','lesson'])
                ->orderBy('id','DESC')->get();

        $html = view('backend.includes.lecture-video_row', compact('videos'))->render();

        return response()->json(['html' => $html]);
    }
}
