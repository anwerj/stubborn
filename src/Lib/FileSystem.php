<?php

namespace Stubborn\Lib;

use Illuminate\Filesystem\Filesystem;

class File extends Filesystem
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var string
     */
    protected $base;

    public function __construct(Config $config, string $base)
    {
        $this->config = $config;

        $this->base = $this->resolveBaseDirectory($base);
    }

    public function write(string $path, string $name, string $content)
    {
        $path = $this->resolveBaseDirectory($this->base . $path) . DIRECTORY_SEPARATOR . $name;

        $this->put($path, $content);

        if ($this->config->printOutput === 'vim')
        {
            echo system("vim $path > `tty`");
        }
        else if ($this->config->printOutput === 'more')
        {
            echo system("more $path > `tty`");
        }
        else if ($this->config->printOutput === 'pbcopy')
        {
            echo system("cat $path | pbcopy");
        }

    }

    public function resolveBaseDirectory(string $base)
    {
        if ($this->isWritable($base) === false)
        {
            $this->makeDirectory($base, 0755, true, true);
        }

        return $base;
    }
}
