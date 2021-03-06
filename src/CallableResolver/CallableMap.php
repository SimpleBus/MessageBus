<?php

namespace SimpleBus\Message\CallableResolver;

use SimpleBus\Message\CallableResolver\Exception\UndefinedCallable;

class CallableMap
{
    /**
     * @var array<string, callable>
     */
    private array $callablesByName;

    private CallableResolver $callableResolver;

    /**
     * @param array<string, callable> $callablesByName
     */
    public function __construct(
        array $callablesByName,
        CallableResolver $callableResolver
    ) {
        $this->callablesByName = $callablesByName;
        $this->callableResolver = $callableResolver;
    }

    public function get(string $name): callable
    {
        if (!array_key_exists($name, $this->callablesByName)) {
            throw new UndefinedCallable(sprintf('Could not find a callable for name "%s"', $name));
        }

        $callable = $this->callablesByName[$name];

        return $this->callableResolver->resolve($callable);
    }
}
