<?php

namespace App\DTO;

use Illuminate\Support\Str;

class FileUpload
{
    private readonly string $justContent;

    private readonly string $name;

    public function __construct(private readonly string $directory, private readonly string $content)
    {
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getJustContent(): string
    {
        if (empty($this->justContent)) {
            $this->justContent = base64_decode(substr($this->content, strpos($this->content, ',') + 1));
        }

        return $this->justContent;
    }

    public function getName(): string
    {
        if (empty($this->name)) {
            $this->name = Str::uuid() . '.' . $this->getExtension();
        }

        return $this->name;
    }

    private function getExtension(): string
    {
        return explode('/', mime_content_type($this->content))[1] ?? '';
    }

    public function getUri(): string
    {
        return $this->getDirectory() . DIRECTORY_SEPARATOR . $this->getName();
    }
}
