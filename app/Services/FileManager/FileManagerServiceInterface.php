<?php

namespace App\Services\FileManager;

use App\DTO\{FileDelete, FileUpload};

interface FileManagerServiceInterface
{
    public function upload(FileUpload $fileUploadDto): string;

    public function delete(FileDelete $fileDeleteDto): bool;
}
