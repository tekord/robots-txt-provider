<?php

namespace Tekord\RobotsTxtProvider;

use Tekord\RobotsTxtProvider\Contracts\ContentProvider;

/**
 * @author Cyrill Tekord
 */
class StringContentProvider implements ContentProvider {
    private ?string $string;

    public function __construct(?string $string) {
        $this->string = $string;
    }

    public function getContent(): ?string {
        return $this->string;
    }
}
