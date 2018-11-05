<?php

namespace Stubborn\Lib;

class Stubs
{
    /**
     * @var
     */
    protected $stubs;

    public function __construct(Config $config, Schema $schema, string $path)
    {
        $classes = $this->resolveClassesForPath($config, $schema, $path);

        $this->stubs = $classes;
    }

    public function iterate(callable $closure)
    {
        foreach ($this->stubs as $name => $stub)
        {
            $closure($stub, $name);
        }
    }

    protected function resolveClassesForPath(Config $config, Schema $schema, string $path = null)
    {
        $pathConfig = $config->stubs()->{$path}();

        $stubs = [];

        foreach ($pathConfig->paths as $name => $_config)
        {
            $stubConfig = $pathConfig->except('paths')->override($_config, $name);

            $class = Stub::resolveClassName($stubConfig->getStubId());

            $stub = (new $class($stubConfig, $schema, $config));

            $stubs[$name] = $stub;
        }

        return $stubs;
    }
}
