<?php

namespace Stubborn\Lib\Ext;

use \Illuminate\Support\Str as Base;

class Str
{
    protected $load = '';

    public function __construct(string $str = null)
    {
        $this->load = $str;
    }
    public function toUpper()
    {
        return $this->clone(strtoupper($this->load));
    }

    public function toLower()
    {
        return $this->clone(strtolower($this->load));
    }

    public function camelCase()
    {
        return $this->clone(camel_case($this->load));
    }

    public function snakeCase()
    {
        return $this->clone(snake_case($this->load));
    }

    public function ucFirst()
    {
        return $this->clone(ucfirst($this->load));
    }

    public function lcFirst()
    {
        return $this->clone(lcfirst($this->load));
    }

    public function toVariable()
    {
        return $this->clone($this->replace('.', '_'));
    }

    public function trim()
    {
        return $this->clone(trim($this->load));
    }

    public function trimLn()
    {
        return $this->clone(trim($this->load, PHP_EOL));
    }

    public function line()
    {
        return $this->clone($this->load . PHP_EOL);
    }

    public function replace(string $search, string $replace)
    {
        return $this->clone(str_replace($search, $replace, $this->load));
    }

    public function padRight(int $length, string $padding = ' ')
    {
        return $this->clone(str_pad($this->load, $length, $padding, STR_PAD_RIGHT));
    }

    public function padLeft(int $length, string $padding = ' ')
    {
        return $this->clone(str_pad($this->load, $length, $padding, STR_PAD_LEFT));
    }

    public function prepend(string $str = null)
    {
        return $this->clone($str . $this->load);
    }

    public function append(string $str = null)
    {
        return $this->clone($this->load . $str);
    }

    public function clone(string $str = null)
    {
        return (new static($str));
    }

    public function str()
    {
        return $this;
    }

    public function toArray()
    {
        return ['load' => $this->load];
    }

    public function __toString()
    {
        return $this->load;
    }

    public function __get(string $method)
    {
        return $this->{$method}();
    }

}
