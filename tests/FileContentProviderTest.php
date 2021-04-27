<?php

namespace Tekord\RobotsTxtProvider\Tests;

use Tekord\RobotsTxtProvider\Exceptions\Exception;
use Tekord\RobotsTxtProvider\FileContentProvider;

/**
 * @author Cyrill Tekord
 */
class FileContentProviderTest extends TestCase {
    public function test() {
        $contentProvider = new FileContentProvider(__DIR__ . "/files/robots.txt");

        $expectedValue = <<<'TXT'
# This is a TEST file

TXT;

        $this->assertEquals($expectedValue, $contentProvider->getContent());
    }

    public function test_missing() {
        $contentProvider = new FileContentProvider(__DIR__ . "/files/this-file-does-not-exist.txt");

        $this->assertEquals('', $contentProvider->getContent());
    }

    public function test_with_exception() {
        $contentProvider = new FileContentProvider(__DIR__ . "/files/this-file-does-not-exist.txt");
        $contentProvider->throwExceptionIfFileIsNotFound = true;

        $this->expectException(Exception::class);

        $contentProvider->getContent();
    }
}
