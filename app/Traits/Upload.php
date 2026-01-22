<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

trait Upload
{
    /**
     * Upload a file to the specified path with optional resizing.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  string $path
     * @param  int|null $width
     * @param  int|null $height
     * @return string|null
     */
    public function uploadFile($file, $path, $width = null, $height = null)
    {
        if (!$file) {
            return null;
        }

        try {
            // Generate a unique filename
            $filename = time() . rand(1, 99) . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path("uploads/$path");

            // Ensure the directory exists
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true);
            }

            // Create the image instance
            $image = Image::make($file);

            // Resize the image if dimensions are provided
            if ($width && $height) {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio(); // Maintain aspect ratio
                    $constraint->upsize(); // Prevent upscale
                });
            }

            // Save the image
            $image->save($uploadPath . '/' . $filename);

            return "$path/$filename";
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            logger()->error("File upload failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete a file from the specified path.
     *
     * @param  string $path
     * @return bool
     */
    public function deleteFile($path)
    {
        $filePath = public_path("uploads/$path");

        if (File::exists($filePath)) {
            return File::delete($filePath);
        }

        return false;
    }
}
