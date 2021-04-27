<?php

namespace Tekord\RobotsTxtProvider\Tests;

use Tekord\RobotsTxtProvider\StringContentProvider;

/**
 * @author Cyrill Tekord
 */
class StringContentProviderTest extends TestCase {
    public function test() {
        $contentProvider = new StringContentProvider(<<<'TXT'
User-Agent: *
Disallow: /
TXT
        );

        $expectedValue = <<<'TXT'
User-Agent: *
Disallow: /
TXT;

        $this->assertEquals($expectedValue, $contentProvider->getContent());
    }

    public function test_null() {
        $contentProvider = new StringContentProvider(null);

        $expectedValue = '';

        $this->assertEquals($expectedValue, $contentProvider->getContent());
    }
}
