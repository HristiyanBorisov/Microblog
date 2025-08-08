<?php

namespace App\Core;

use Slim\Http\UploadedFile;

interface ImageServiceInterface
{
    public function upload(UploadedFile $uploadedFile);
}
