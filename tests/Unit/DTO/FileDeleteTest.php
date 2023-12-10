<?php

namespace Tests\Unit\DTO;

use App\DTO\FileDelete;
use Tests\TestCase;

class FileDeleteTest extends TestCase
{
    private readonly FileDelete $fileDeleteDto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileDeleteDto = new FileDelete(uri: 'path-to-file/file.png');
    }

    /**
     * @test
     */
    public function shouldGetUri()
    {
        $uri = $this->fileDeleteDto->getUri();

        $this->assertIsString($uri);
        $this->assertNotEmpty($uri);
        $this->assertEquals('path-to-file/file.png', $uri);
    }
}
