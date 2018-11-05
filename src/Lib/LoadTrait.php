<?php

namespace Stubborn\Lib;

trait LoadTrait
{
    protected $load = [];

    protected $loadPosition;

    public function __construct(array $load, string $_id = null)
    {
        $this->load = $load;
        $this->setId($_id ?? $this->getLoadId());
    }

    public function getId(string $append = null)
    {
        return $this->_id . ($append ? ".$append" : '');
    }

    public function setId(string $id)
    {
        $this->_id = $id;
        return $this;
    }

    public function appendId(string $id)
    {
        return $this->setId($this->getId($id));
    }

    public function append(array $load, string $_id = null)
    {
        return (new static($this->merge($load, $this->load), $this->getId($_id)));
    }

    public function override(array $load, string $_id = null)
    {
        return (new static($this->merge($this->load, $load), $this->getId($_id)));
    }

    public function except($keys, string $_id = null)
    {
        return (new static(array_except($this->load, $keys), $this->getId($_id)));
    }

    public function iterate(callable $callback, string $_id = null)
    {
        $out = array_map(
            function($load, $key) use ($callback)
            {
                $callback($load, $key);
            },
            $this->load);

        return (new static($out, $this->getId($_id)));
    }

    public function toArray()
    {
        return $this->load;
    }

    public function __get(string $name)
    {
        return array_get($this->load, snake_case($name));
    }

    public function __call(string $name, $args)
    {
        $load = $this->{$name};

        if (is_array($load) === false)
        {
            $this->handleMissingLoad($name);
        }

        return (new static($load, $this->getId($name)));
    }

    protected function getLoadId()
    {
        return 'root';
    }

    protected function merge(array $array, array $replace)
    {
        return array_replace_recursive($array, $replace);
    }

    protected function handleMissingLoad($key)
    {
        Error::report('Load is not valid array', [$key]);
    }
}
