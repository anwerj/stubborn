<?php

namespace Stubborn;

use Stubborn\Lib\File;
use Stubborn\Lib\Stub;
use Stubborn\Lib\Stubs;
use Stubborn\Lib\Config;
use Stubborn\Lib\Schema;

class Generator
{
    const ROOT          = 'root';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @var Stubs
     */
    protected $stubs;

    /**
     * @var File
     */
    protected $file;

    /**
     * Generator constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    public function loadSchema(string $path)
    {
        $this->schema = new Schema($this->config, $path);
    }

    public function loadStubs(string $path = null)
    {
        $this->stubs = new Stubs($this->config, $this->schema, $path);
    }

    public function loadFilesytem(string $directory = null)
    {
        $this->file = new File($this->config, $directory);
    }

    public function generate()
    {
        $this->stubs->iterate(
            function(Stub $stub, $name)
            {
                $content = $stub->generate();
                $path    = $stub->getFilePath();
                $name    = $stub->getFileName();

                $this->file->write($path, $name, $content);
            });
    }
}
