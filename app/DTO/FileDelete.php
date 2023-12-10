<?php

namespace App\DTO;

class FileDelete
{
    public function __construct(private readonly string $uri)
    {
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
