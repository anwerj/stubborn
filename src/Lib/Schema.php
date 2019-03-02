<?php

namespace Stubborn\Lib;

use Stubborn\Lib\Json\Property;
use JsonSchema\SchemaStorage;

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

        $storage = new SchemaStorage;

        $schema = (object) ['$ref' => 'file://' . $path];

//        $storage->addSchema(SchemaStorage::INTERNAL_PROVIDED_SCHEMA_URI, $schema);
//
//        $load = $storage->getSchema(SchemaStorage::INTERNAL_PROVIDED_SCHEMA_URI);

        $this->root = new Property($storage, 'root', $schema, null);
    }
}
