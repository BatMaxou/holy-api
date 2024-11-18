<?php

namespace App\Service\File;

class Uploader
{
    private const UPLOAD_PATERN = 'uploads{{directory}}/{{name}}.png';

    private string $currentDirectory;

    public function __construct()
    {
        $this->currentDirectory = '';
    }

    public function uploadFile(string $name, string $imageUrl): bool
    {
        $path = str_replace('{{directory}}', $this->currentDirectory, self::UPLOAD_PATERN);
        $path = str_replace('{{name}}', $name, $path);

        return copy($imageUrl, $path);
    }

    public function getCurrentDirectory(): string
    {
        return $this->currentDirectory;
    }

    public function setCurrentDirectory(?string $currentDirectory): void
    {
        $this->currentDirectory = null === $currentDirectory ? '' : sprintf('/%s', $currentDirectory);
    }
}
