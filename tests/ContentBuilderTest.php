<?php

namespace Tekord\RobotsTxtProvider\Tests;

use BadMethodCallException;
use Exception;
use Tekord\RobotsTxtProvider\ContentBuilder;

/**
 * @author Cyrill Tekord
 */
class ContentBuilderTest extends TestCase {
    public function test_direct_calls() {
        $content = ContentBuilder::make()
            ->line("User-Agent: *")
            ->emptyLine()
            ->comment("This is a comment")
            ->parameter("Host", "https://example.com")
            ->allow("/about")
            ->disallow("/login")
            ->build();

        $expectedValue = <<<'TXT'
User-Agent: *

# This is a comment
Host: https://example.com
Allow: /about
Disallow: /login
TXT;

        $this->assertEquals($expectedValue, $content);
    }

    public function test_conditional_calls() {
        $alwaysTrue = true;
        $alwaysFalse = false;

        $trueCallback = function () {
            return true;
        };

        $falseCallback = function () {
            return false;
        };

        $content = ContentBuilder::make()
            ->lineIf($alwaysTrue, "User-Agent: *")
            ->emptyLineIf($trueCallback)
            ->commentIf($alwaysFalse, "This is a comment")
            ->parameterIf($trueCallback, "Host", "https://example.com")
            ->allowIf($falseCallback, "/about")
            ->disallowIf($alwaysTrue, "/login")
            ->build();

        $expectedValue = <<<'TXT'
User-Agent: *

Host: https://example.com
Disallow: /login
TXT;

        $this->assertEquals($expectedValue, $content);
    }

    public function test_bad_calls() {
        try {
            ContentBuilder::make()
                ->nonExistingMethod()
                ->build();
        }
        catch (Exception $e) {
            $this->assertException(BadMethodCallException::class, $e);
        }

        try {
            ContentBuilder::make()
                ->allowSomething()
                ->build();
        }
        catch (Exception $e) {
            $this->assertException(BadMethodCallException::class, $e);
        }

        try {
            ContentBuilder::make()
                ->getLinesIf()
                ->build();
        }
        catch (Exception $e) {
            $this->assertException(BadMethodCallException::class, $e);
        }
    }

    protected function assertException($expectedType, $actualException) {
        $this->assertInstanceOf($expectedType, $actualException);
    }
}
