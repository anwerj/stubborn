<?php

namespace Stubborn\Lib\Json;

use stdClass;
use Stubborn\Lib\Ext\Str;
use JsonSchema\SchemaStorage;

class Property
{
    public $type;
    public $title;
    public $description;
    public $default;
    public $pattern;
    public $maxLength;
    public $examples;
    public $required;
    public $properties;

    protected $_name                = 'root';
    protected $_data                = [];
    protected $_parent              = null;
    protected $_properties          = [];
    protected $_propertyMaxLength   = 0;
    protected $_isRequired          = false;

    public function __construct(SchemaStorage $storage, string $name, stdClass $schema, Property $parent = null)
    {
        $data = $storage->resolveRefSchema($schema);

        $this->_name        = $name;
        $this->_data        = $data;

        $this->type         = $data->type           ?? 'string';
        $this->title        = $data->title          ?? null;
        $this->description  = $data->description    ?? null;
        $this->default      = $data->default        ?? null;
        $this->pattern      = $data->pattern        ?? null;
        $this->maxLength    = $data->maxLength      ?? null;
        $this->examples     = $data->examples       ?? (new stdClass);
        $this->required     = $data->required       ?? (new stdClass);
        $this->properties   = $data->properties     ?? (new stdClass);

        $this->handleParent($parent);
        $this->handleProperties($storage);
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

    protected function handleProperties(SchemaStorage $storage)
    {
        foreach ($this->properties as $name => $property)
        {
            $this->_properties[$name] = new Property($storage, $name, $property, $this);

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
