<?php

namespace App\Services\FileManager;

use App\DTO\{FileDelete, FileUpload};
use App\Exceptions\FileException;
use Illuminate\Support\Facades\Storage;

class FileManagerService implements FileManagerServiceInterface
{
    /**
     * @throws FileException
     */
    public function upload(FileUpload $fileUploadDto): string
    {
        $uri = $fileUploadDto->getUri();

        if (!Storage::disk()->put($uri, $fileUploadDto->getJustContent())) {
            throw new FileException(__('exception.file_manager.upload.fail'));
        }

        return $uri;
    }

    public function delete(FileDelete $fileDeleteDto): bool
    {
        $uri = $fileDeleteDto->getUri();

        if (!Storage::disk()->delete($uri)) {
            throw new FileException(__('exception.file_manager.delete.fail'));
        }

        return true;
    }
}
