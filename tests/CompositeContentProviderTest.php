<?php

namespace Tekord\RobotsTxtProvider\Tests;

use Tekord\RobotsTxtProvider\CallbackContentProvider;
use Tekord\RobotsTxtProvider\CompositeContentProvider;
use Tekord\RobotsTxtProvider\ContentBuilder;
use Tekord\RobotsTxtProvider\FileContentProvider;

/**
 * @author Cyrill Tekord
 */
class CompositeContentProviderTest extends TestCase {
    public function test_first_provider_is_ok() {
        $fileContentProvider = new FileContentProvider(__DIR__ . "/files/robots.production.txt");

        $fallbackContentProvider = new CallbackContentProvider(function () {
            return ContentBuilder::make()
                ->comment("Nothing interesting here")
                ->allow("/")
                ->build();
        });

        $compositeContentProvider = (new CompositeContentProvider())
            ->addContentProvider($fileContentProvider)
            ->addContentProvider($fallbackContentProvider);


        $expectedValue = <<<'TXT'
# This is a PRODUCTION file

TXT;

        $this->assertEquals($expectedValue, $compositeContentProvider->getContent());
    }

    public function test_first_provider_is_null_and_the_second_one_is_ok() {
        $fileContentProvider = new FileContentProvider(__DIR__ . "/files/non-existing-file.txt");

        $fallbackContentProvider = new CallbackContentProvider(function () {
            return ContentBuilder::make()
                ->comment("Nothing interesting here")
                ->allow("/")
                ->build();
        });

        $compositeContentProvider = (new CompositeContentProvider())
            ->addContentProvider($fileContentProvider)
            ->addContentProvider($fallbackContentProvider);


        $expectedValue = <<<'TXT'
# Nothing interesting here
Allow: /
TXT;

        $this->assertEquals($expectedValue, $compositeContentProvider->getContent());
    }

    public function test_no_content_from_providers() {
        $fileContentProvider = new FileContentProvider(__DIR__ . "/files/non-existing-file.txt");

        $fallbackContentProvider = new CallbackContentProvider(function () {
            return null;
        });

        $compositeContentProvider = (new CompositeContentProvider())
            ->addContentProvider($fileContentProvider)
            ->addContentProvider($fallbackContentProvider);

        $expectedValue = null;

        $this->assertEquals($expectedValue, $compositeContentProvider->getContent());
    }
}
