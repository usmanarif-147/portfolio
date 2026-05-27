<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

/**
 * Reusable image optimization for all uploads.
 *
 * Every image is scaled DOWN to fit a max box (aspect ratio kept, never cropped,
 * never upscaled) and re-encoded to WebP. If WebP can't be produced it falls back
 * to JPEG so an upload never fails. Files are stored on the `public` disk.
 *
 * Used by: ProfileEdit, ProjectService (and future modules — pass any directory).
 */
class ImageUploadService
{
    private const MAX_DIMENSION = 1600;

    private const QUALITY = 80;

    private function manager(): ImageManager
    {
        return new ImageManager(new Driver);
    }

    /**
     * Compress a file → returns ['contents' => binary, 'extension' => 'webp'|'jpg'].
     */
    public function compress(UploadedFile $file, int $maxDim = self::MAX_DIMENSION, int $quality = self::QUALITY): array
    {
        $image = $this->manager()->decode($file->getRealPath())->scaleDown($maxDim, $maxDim);

        try {
            return [
                'contents' => (string) $image->encode(new WebpEncoder(quality: $quality, strip: true)),
                'extension' => 'webp',
            ];
        } catch (\Throwable) {
            // Environment without WebP support — keep uploads working with JPEG.
            return [
                'contents' => (string) $image->encode(new JpegEncoder(quality: $quality)),
                'extension' => 'jpg',
            ];
        }
    }

    /**
     * Compress and store on the public disk. Returns the stored relative path.
     */
    public function store(UploadedFile $file, string $directory, int $maxDim = self::MAX_DIMENSION, int $quality = self::QUALITY): string
    {
        $compressed = $this->compress($file, $maxDim, $quality);
        $path = trim($directory, '/').'/'.Str::uuid().'.'.$compressed['extension'];

        Storage::disk('public')->put($path, $compressed['contents']);

        return $path;
    }

    /**
     * Original vs compressed byte sizes — for the before/after info bar.
     *
     * @return array{original:int, compressed:int}
     */
    public function sizeInfo(UploadedFile $file, int $maxDim = self::MAX_DIMENSION, int $quality = self::QUALITY): array
    {
        return [
            'original' => (int) $file->getSize(),
            'compressed' => strlen($this->compress($file, $maxDim, $quality)['contents']),
        ];
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function deleteDirectory(string $directory): void
    {
        $directory = trim($directory, '/');

        if ($directory !== '' && Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->deleteDirectory($directory);
        }
    }
}
