<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Compress and store an uploaded image automatically.
     * Resizes proportionally (max 1920x1920) and compresses quality to ~80%
     * to significantly save disk storage while maintaining visual clarity.
     *
     * @param UploadedFile $file
     * @param string $directory Target directory on disk
     * @param string $disk Storage disk
     * @param int $maxWidth Max width in pixels (1920)
     * @param int $maxHeight Max height in pixels (1920)
     * @param int $quality Compression quality 1-100 (80)
     * @return array ['path' => string, 'original_name' => string]
     */
    public static function compressAndStore(
        UploadedFile $file,
        string $directory,
        string $disk = 'public',
        int $maxWidth = 1920,
        int $maxHeight = 1920,
        int $quality = 80
    ): array {
        $originalName = $file->getClientOriginalName();
        $ext = strtolower($file->getClientOriginalExtension());
        $cleanOriginalName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalName);
        $fileName = time() . '_' . uniqid() . '_' . $cleanOriginalName;
        $targetPath = trim($directory, '/') . '/' . $fileName;

        // If GD extension is missing, safely fallback to storeAs
        if (!extension_loaded('gd')) {
            $path = $file->storeAs($directory, $fileName, $disk);
            return [
                'path' => $path,
                'original_name' => $originalName,
            ];
        }

        $realPath = $file->getRealPath();
        $image = null;

        try {
            $mime = $file->getMimeType();
            if ($mime === 'image/jpeg' || in_array($ext, ['jpg', 'jpeg'])) {
                $image = @imagecreatefromjpeg($realPath);
            } elseif ($mime === 'image/png' || $ext === 'png') {
                $image = @imagecreatefrompng($realPath);
            } elseif ($mime === 'image/webp' || $ext === 'webp') {
                if (function_exists('imagecreatefromwebp')) {
                    $image = @imagecreatefromwebp($realPath);
                }
            } elseif ($mime === 'image/gif' || $ext === 'gif') {
                $image = @imagecreatefromgif($realPath);
            } elseif ($mime === 'image/bmp' || $ext === 'bmp') {
                if (function_exists('imagecreatefrombmp')) {
                    $image = @imagecreatefrombmp($realPath);
                }
            }

            // Fallback for non-raster formats (SVG, etc.) or unsupported formats
            if (!$image) {
                $path = $file->storeAs($directory, $fileName, $disk);
                return [
                    'path' => $path,
                    'original_name' => $originalName,
                ];
            }

            // Handle EXIF orientation for smartphone JPEG photos
            if (function_exists('exif_read_data') && ($mime === 'image/jpeg' || in_array($ext, ['jpg', 'jpeg']))) {
                try {
                    $exif = @exif_read_data($realPath);
                    if (!empty($exif['Orientation'])) {
                        switch ($exif['Orientation']) {
                            case 3:
                                $image = imagerotate($image, 180, 0);
                                break;
                            case 6:
                                $image = imagerotate($image, -90, 0);
                                break;
                            case 8:
                                $image = imagerotate($image, 90, 0);
                                break;
                        }
                    }
                } catch (\Throwable $e) {
                    // Ignore EXIF errors
                }
            }

            $origWidth = imagesx($image);
            $origHeight = imagesy($image);

            $newWidth = $origWidth;
            $newHeight = $origHeight;

            if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
                $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
                $newWidth = (int) round($origWidth * $ratio);
                $newHeight = (int) round($origHeight * $ratio);
            }

            $newImage = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve alpha channel for PNG and WebP
            if (in_array($ext, ['png', 'webp'])) {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            ob_start();
            if ($ext === 'png') {
                imagepng($newImage, null, 7);
            } elseif ($ext === 'webp' && function_exists('imagewebp')) {
                imagewebp($newImage, null, $quality);
            } else {
                imagejpeg($newImage, null, $quality);
            }
            $compressedData = ob_get_clean();

            imagedestroy($image);
            imagedestroy($newImage);

            Storage::disk($disk)->put($targetPath, $compressedData);

            return [
                'path' => $targetPath,
                'original_name' => $originalName,
            ];
        } catch (\Throwable $e) {
            $path = $file->storeAs($directory, $fileName, $disk);
            return [
                'path' => $path,
                'original_name' => $originalName,
            ];
        }
    }
}
