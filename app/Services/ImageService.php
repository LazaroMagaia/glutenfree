<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    public function upload(
        UploadedFile $image,
        string $directory = 'categories'
    ): string {
        return $image->store($directory, 'public');
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Elimina vários ficheiros.
     */
    public function deleteMany(array $paths): void
    {
        $paths = array_values(array_filter($paths));

        if (empty($paths)) {
            return;
        }

        Storage::disk('public')->delete($paths);
    }
}