<?php

namespace Tekord\RobotsTxtProvider\Tests;

use Tekord\RobotsTxtProvider\CallbackContentProvider;

/**
 * @author Cyrill Tekord
 */
class CallbackContentProviderTest extends TestCase {
    public function test() {
        $contentProvider = new CallbackContentProvider(function ($data) {
            return <<<'TXT'
User-Agent: *
Disallow: /
TXT;
        });

        $expectedValue = <<<'TXT'
User-Agent: *
Disallow: /
TXT;

        $this->assertEquals($expectedValue, $contentProvider->getContent());
    }

    public function test_with_data() {
        $contentProvider = new CallbackContentProvider(function ($data) {
            $disallows = array_map(function ($i) {
                return "Disallow: $i";
            }, $data);

            $disallowsAsString = implode("\n", $disallows);

            return <<<TXT
User-Agent: *
$disallowsAsString
TXT;
        }, [
            '/login',
            '/admin'
        ]);

        $expectedValue = <<<'TXT'
User-Agent: *
Disallow: /login
Disallow: /admin
TXT;

        $this->assertEquals($expectedValue, $contentProvider->getContent());
    }
}
