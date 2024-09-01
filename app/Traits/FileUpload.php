<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

trait  FileUpload
{

    public function uploadImageTrait($file, $path, $oldImageUrl = null)
    {
        // Generate unique name for the image
        $imageName = $file->hashName();

        // Store the new image
        $imageUrl = $file->storeAs($path, $imageName, 'public');
        $url = url(Storage::url($imageUrl));



        if ($oldImageUrl) {

            // Parse the old image URL to get the path
            $parsedUrl = parse_url($oldImageUrl);

            if (isset($parsedUrl['path'])) {
                // Extract path from URL
                $pathToDelete = $parsedUrl['path'];

                // Remove the part before '/storage'
                if (strpos($pathToDelete, '/images') !== false) {
                    $pathToDelete = substr($pathToDelete, strpos($pathToDelete, '/images'));
                }
                // Delete the file if it exists
                if (Storage::disk('public')->exists($pathToDelete)) {
                    Storage::disk('public')->delete($pathToDelete);
                }
            }
        }

        return $url;
    }

    public function deleteImageByUrl($imageUrl)
    {
        $parsedUrl = parse_url($imageUrl);

        if (isset($parsedUrl['path'])) {
            // Extract path from URL
            $pathToDelete = $parsedUrl['path'];

            // Remove the part before '/storage'
            if (strpos($pathToDelete, '/images') !== false) {
                $pathToDelete = substr($pathToDelete, strpos($pathToDelete, '/images'));
            }

            // Delete the file if it exists
            if (Storage::disk('public')->exists($pathToDelete)) {
                Storage::disk('public')->delete($pathToDelete);
                return true;
            }

            return false;
        }
    }
}
