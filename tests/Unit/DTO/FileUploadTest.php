<?php

namespace Tests\Unit\DTO;

use App\DTO\FileUpload;
use Tests\Fixture\ImageFixture;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    private readonly FileUpload $fileUploadDto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileUploadDto = new FileUpload(directory: 'directory-from-file', content: ImageFixture::getImageBase64Encoded());
    }

    /**
     * @test
     */
    public function shouldGetUri()
    {
        $uri = $this->fileUploadDto->getUri();

        $this->assertIsString($uri);
    }
    /**
     * @test
     */
    public function shouldGetPath()
    {
        $path = $this->fileUploadDto->getDirectory();

        $this->assertIsString($path);
        $this->assertEquals('directory-from-file', $path);
    }

    /**
     * @test
     */
    public function shouldGetContent()
    {
        $content = $this->fileUploadDto->getContent();

        $this->assertIsString($content);
        $this->assertNotEmpty($content);
        $this->assertTrue(str_contains($content, 'base64'));
    }

    /**
     * @test
     */
    public function shouldGetJustContent()
    {
        $content = $this->fileUploadDto->getJustContent();

        $this->assertIsString($content);
        $this->assertNotEmpty($content);
        $this->assertFalse(str_contains($content, 'base64'));
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        $name = $this->fileUploadDto->getName();

        $this->assertIsString($name);
        $this->assertNotEmpty($name);
        $this->assertTrue(str_contains($name, 'png'));
    }
}
