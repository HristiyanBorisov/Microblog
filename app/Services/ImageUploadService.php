<?php

namespace App\Services;

use App\Core\ImageServiceInterface;
use Slim\Http\UploadedFile;

class ImageUploadService implements ImageServiceInterface
{
    public function upload(UploadedFile $uploadedFile): string
    {
        $targetDir = __DIR__ . '/../../public/images/';


        $originalFileName = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

        $filename = uniqid() . '_' .preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalFileName) . '.' . $extension;

        $targetPath = $targetDir . $filename;

        $uploadedFile->moveTo($targetPath);

        return '/images/' . $filename;
    }
}
