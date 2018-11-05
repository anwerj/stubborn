<?php

namespace Stubborn\Lib\Json;

use Stubborn\Lib\Ext\Str;

class Property
{
    public $type;
    public $title;
    public $default;
    public $examples;
    public $pattern;
    public $required;
    public $maxLength;

    protected $_name                = 'root';
    protected $_data                = [];
    protected $_parent              = null;
    protected $_properties          = [];
    protected $_propertyMaxLength   = 0;
    protected $_isRequired          = false;

    public function __construct(string $name, array $data, Property $parent = null)
    {
        $this->_name        = $name;
        $this->_data        = $data;

        $this->type         = $data['type'] ?? 'string';
        $this->title        = $data['title'] ?? null;
        $this->default      = $data['default'] ?? null;
        $this->examples     = $data['examples'] ?? [];
        $this->pattern      = $data['pattern'] ?? null;
        $this->required     = $data['required'] ?? [];
        $this->maxLength    = $data['maxLength'] ?? null;

        $this->handleParent($parent);
        $this->handleProperties($data['properties'] ?? []);
    }

    public function name(): string
    {
        return $this->_name;
    }

    public function parent()
    {
        return $this->_parent;
    }

    public function data()
    {
        return $this->_data;
    }

    public function properties(): array
    {
        return $this->_properties;
    }

    public function propertyMaxLength(): int
    {
        return $this->_propertyMaxLength;
    }

    public function isRequired(): bool
    {
        return $this->_isRequired;
    }

    public function types()
    {
        if (is_string($this->type))
        {
            return [$this->type];
        }

        return $this->type;
    }

    public function isNullable(): bool
    {
        foreach ($this->types() as $type)
        {
            if (($type === null) or ($type === 'null'))
            {
                return true;
            }
        }

        return false;
    }

    protected function handleParent(Property $parent = null)
    {
        if (is_null($parent))
        {
            return;
        }
        $this->_parent = $parent;

        $this->_isRequired = in_array($this->name(), $this->_parent->required);
    }

    protected function handleProperties(array $properties)
    {
        foreach ($properties as $name => $property)
        {
            $this->_properties[$name] = new Property($name, $property, $this);

            if(strlen($name) > $this->_propertyMaxLength)
            {
                $this->_propertyMaxLength = strlen($name);
            }
        }
    }

    public function __get(string $propertyName)
    {
        if (starts_with($propertyName, 'name'))
        {
            $method = substr($propertyName, 4);
            return (new Str($this->name()))->{$method};
        }

        foreach ($this->_properties as $name => $property)
        {
            if (starts_with($propertyName, $name))
            {
                $method = substr($propertyName, strlen($name));
                return (new Str($property->name()))->{$method};
            }
        }
    }
}
