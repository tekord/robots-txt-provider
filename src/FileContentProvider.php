<?php

namespace Tekord\RobotsTxtProvider;

use Tekord\RobotsTxtProvider\Contracts\ContentProvider;

/**
 * @author Cyrill Tekord
 */
class FileContentProvider implements ContentProvider {
    public bool $throwExceptionIfFileIsNotFound = false;

    private string $filePath;

    public function __construct(string $string) {
        $this->filePath = $string;
    }

    public function getContent(): ?string {
        if (file_exists($this->filePath)) {
            return file_get_contents($this->filePath);
        }

        if ($this->throwExceptionIfFileIsNotFound)
            throw new \Exception("File not found: " . $this->filePath);

        return null;
    }
}
