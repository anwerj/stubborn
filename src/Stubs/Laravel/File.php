<?php

namespace Stubborn\Stubs\Laravel;

use Stubborn\Ext;
use Stubborn\Lib\Stub;
use Stubborn\Stubs\PHP70;

class File extends PHP70
{
    use Ext\Laravel54;

    public function namespace()
    {
        return $this->config->namespace;
    }

    public function title()
    {
        $title = $this->schema->root->title;

        $title = str_ireplace('the ', '', $title);
        $title = str_ireplace(' Schema', '', $title);

        return $title;
    }

    public function className()
    {
        return ucfirst(camel_case($this->title()));
    }

    public function getFileName()
    {
        return $this->className() . '.php';
    }
}
