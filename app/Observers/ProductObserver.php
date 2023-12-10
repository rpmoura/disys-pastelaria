<?php

namespace App\Observers;

use App\DTO\FileDelete;
use App\Models\Product;
use App\Services\FileManager\FileManagerServiceInterface;

class ProductObserver
{
    public function __construct(private readonly FileManagerServiceInterface $fileManagerService)
    {
    }

    public function updated(Product $product): void
    {
        if ($product->isDirty('photo')) {
            $this->deleteFile($product->getOriginal('photo'));
        }
    }

    public function deleted(Product $product): void
    {
        $this->deleteFile($product->photo);
    }

    private function deleteFile(string $uri): void
    {
        $fileDeleteDto = new FileDelete(uri: $uri);
        $this->fileManagerService->delete($fileDeleteDto);
    }
}
