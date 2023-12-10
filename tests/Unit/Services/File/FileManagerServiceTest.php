<?php

namespace Services\File;

use App\DTO\{FileDelete, FileUpload};
use App\Exceptions\FileException;
use App\Services\FileManager\{FileManagerService, FileManagerServiceInterface};
use Illuminate\Support\Facades\Storage;
use Tests\Fixture\ImageFixture;
use Tests\TestCase;

class FileManagerServiceTest extends TestCase
{
    private readonly FileManagerServiceInterface $fileManagerService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileManagerService = new FileManagerService();
    }

    /**
     * @test
     */
    public function shouldUploadFileSuccessfully()
    {
        $fileUploadDto = \Mockery::mock(FileUpload::class);
        $this->app->instance(FileUpload::class, $fileUploadDto);
        $fileUploadDto
            ->shouldReceive('getUri')
            ->once()
            ->andReturn('products/test.png');
        $fileUploadDto
            ->shouldReceive('getJustContent')
            ->once()
            ->andReturns(ImageFixture::getJustContentFromImageBase64Encoded());

        Storage::shouldReceive('disk')->once()->andReturnSelf();
        Storage::shouldReceive('put')
            ->once()
            ->withAnyArgs()
            ->andReturnTrue();

        $result = $this->fileManagerService->upload($fileUploadDto);
        $this->assertIsString($result);
        $this->assertEquals('products/test.png', $result);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionOnUploadError()
    {
        $fileUploadDto = \Mockery::mock(FileUpload::class);
        $this->app->instance(FileUpload::class, $fileUploadDto);
        $fileUploadDto
            ->shouldReceive('getUri')
            ->once()
            ->andReturn('products/test.png');
        $fileUploadDto
            ->shouldReceive('getJustContent')
            ->once()
            ->andReturns(ImageFixture::getJustContentFromImageBase64Encoded());

        Storage::shouldReceive('disk')->once()->andReturnSelf();
        Storage::shouldReceive('put')
            ->once()
            ->withAnyArgs()
            ->andReturnFalse();

        $this->expectException(FileException::class);

        $this->fileManagerService->upload($fileUploadDto);
    }

    /**
     * @test
     */
    public function shouldDeleteFileSuccessfully()
    {
        $fileDeleteDto = \Mockery::mock(FileDelete::class);
        $this->app->instance(FileDelete::class, $fileDeleteDto);
        $fileDeleteDto
            ->shouldReceive('getUri')
            ->once()
            ->andReturn('products/test.png');

        Storage::shouldReceive('disk')->once()->andReturnSelf();
        Storage::shouldReceive('delete')
            ->once()
            ->withAnyArgs()
            ->andReturnTrue();

        $result = $this->fileManagerService->delete($fileDeleteDto);
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionOnDeleteError()
    {
        $fileDeleteDto = \Mockery::mock(FileDelete::class);
        $this->app->instance(FileDelete::class, $fileDeleteDto);
        $fileDeleteDto
            ->shouldReceive('getUri')
            ->once()
            ->andReturn('products/test.png');

        Storage::shouldReceive('disk')->once()->andReturnSelf();
        Storage::shouldReceive('delete')
            ->once()
            ->withAnyArgs()
            ->andReturnFalse();

        $this->expectException(FileException::class);

        $this->fileManagerService->delete($fileDeleteDto);
    }
}
