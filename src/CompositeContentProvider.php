<?php

namespace Tekord\RobotsTxtProvider;

use Tekord\RobotsTxtProvider\Contracts\ContentProvider;

/**
 * @author Cyrill Tekord
 */
final class CompositeContentProvider implements ContentProvider {
    /** @var ContentProvider[] */
    private $providers = [];

    public function addContentProvider(?ContentProvider $instance) {
        if ($instance === null)
            return $this;

        $this->providers[] = $instance;

        return $this;
    }

    public function getProviders() {
        return $this->providers;
    }

    public function getContent(): ?string {
        foreach ($this->providers as $provider) {
            $content = $provider->getContent();

            if ($content !== null)
                return $content;
        }

        return null;
    }
}
