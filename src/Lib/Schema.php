<?php

namespace Stubborn\Lib;

use Stubborn\Lib\Json\Property;

class Schema
{
    /**
     * @var Property
     */
    public $root;

    public $path;

    public function __construct(Config $config, $path)
    {
        $this->path = $path;

        $load = json_decode(file_get_contents($path), true);

        $this->root = new Property('root', $load, null);
    }
}
