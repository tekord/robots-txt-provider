<?php

namespace Tekord\RobotsTxtProvider;

use Tekord\RobotsTxtProvider\Contracts\ContentProvider;

/**
 * @author Cyrill Tekord
 */
class CallbackContentProvider implements ContentProvider {
    /** @var callable */
    private $builderCallback;

    /** @var mixed */
    private $data;

    public function __construct(
        callable $builderCallback,
        $data = null
    ) {
        $this->builderCallback = $builderCallback;
        $this->data = $data;
    }

    public function getBuilderCallback() {
        return $this->builderCallback;
    }

    public function getData() {
        return $this->data;
    }

    public function getContent(): string {
        return call_user_func($this->builderCallback, $this->data);
    }
}
