<?php

namespace Stubborn\Stubs\Laravel;

class Controller extends File
{
    public function namespace()
    {
        return parent::namespace();
    }

    public function imports()
    {
        return $this->lines('use Illuminate\Http\Controller;');
    }

    public function className()
    {
        return ucfirst(camel_case($this->title())) . 'Controller';
    }
}
