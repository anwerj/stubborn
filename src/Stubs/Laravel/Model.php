<?php

namespace Stubborn\Stubs\Laravel;

class Model extends File
{
    public function imports()
    {
        return $this->lines('use Illuminate\Database\Model;');
    }

    public function tableName()
    {
        return strtolower(camel_case($this->title()));
    }
}
