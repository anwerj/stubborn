<?php

namespace Stubborn\Stubs;

use Stubborn\Ext;
use Stubborn\Lib\Stub;

class PHP70 extends Stub
{
    use Ext\Php70;

    public function fileOpen()
    {
        return '<?php';
    }

    public function classOpen()
    {
        $className = $this->className();

        return $this->lines("class $className");
    }

    public function classClose()
    {
        return $this->lines('}') . $this->line();
    }
}
