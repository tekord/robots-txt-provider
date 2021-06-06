<?php

namespace Tekord\RobotsTxtProvider;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * @method self lineIf(callable|bool $predicate, mixed $value)
 * @method self emptyLineIf(callable|bool $predicate)
 * @method self parameterIf(callable|bool $predicate, string $key, mixed $value)
 * @method self commentIf(callable|bool $predicate, mixed $value)
 * @method self allowIf(callable|bool $predicate, mixed $value)
 * @method self disallowIf(callable|bool $predicate, mixed $value)
 *
 * @author Cyrill Tekord
 */
class ContentBuilder {

    public $endOfLineCharacter = "\n";

    /** @var string[] */
    protected $lines = [];

    protected $methodsThatSupportConditionalCall = [
        "line",
        "emptyLine",
        "parameter",
        "comment",
        "allow",
        "disallow",
    ];

    public function __construct(
        string $endOfLineCharacter = null
    ) {
        if ($endOfLineCharacter !== null)
            $this->endOfLineCharacter = $endOfLineCharacter;
    }

    public static function make() {
        return new static();
    }

    public function build(): string {
        return implode($this->endOfLineCharacter, $this->lines);
    }

    public function getEndOfLineCharacter() {
        return $this->endOfLineCharacter;
    }

    public function setEndOfLineCharacter(string $endOfLineCharacter) {
        $this->endOfLineCharacter = $endOfLineCharacter;
    }

    public function getLines() {
        return $this->lines;
    }

    protected function isPredicate($argument) {
        return is_callable($argument) || is_bool($argument);
    }

    protected function resolvePredicate($predicate): bool {
        if (is_callable($predicate))
            return (bool)$predicate();

        return (bool)$predicate;
    }

    public function __call($name, $arguments) {
        $ending = substr($name, -2);

        if ($ending == "If") {
            $actualMethodName = substr($name, 0, -2);

            if (!method_exists($this, $actualMethodName))
                throw new BadMethodCallException("Method does not exist: " . $actualMethodName);

            if (!in_array($actualMethodName, $this->methodsThatSupportConditionalCall))
                throw new BadMethodCallException("Method does not support conditional call: " . $actualMethodName);

            if (count($arguments) == 0)
                throw new BadMethodCallException("Method expects arguments, but nothing provided: " . $actualMethodName);

            $predicate = $arguments[0];

            if ($this->isPredicate($predicate)) {
                $actualArguments = array_slice($arguments, 1);

                if ($this->resolvePredicate($predicate))
                    return call_user_func_array([$this, $actualMethodName], $actualArguments);
                else
                    return $this;
            } else {
                throw new InvalidArgumentException();
            }
        }

        if (!method_exists($this, $name))
            throw new BadMethodCallException("Method does not exist: " . $name);

        return call_user_func_array([$this, $name], $arguments);
    }

    public function line(string $value) {
        $this->lines[] = $value;

        return $this;
    }

    public function emptyLine() {
        $this->lines[] = null;

        return $this;
    }

    public function parameter(string $key, string $value) {
        $this->lines[] = "$key: $value";

        return $this;
    }

    public function comment(string $value) {
        $this->lines[] = "# $value";

        return $this;
    }

    public function allow($url) {
        return $this->parameter("Allow", $url);
    }

    public function disallow($url) {
        return $this->parameter("Disallow", $url);
    }
}
