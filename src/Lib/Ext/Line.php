<?php

namespace Stubborn\Ext;

use Stubborn\Lib\Ext\Str;

trait Line
{
    protected $_lineIndentation = 0;

    public function indent(int $count = 1)
    {
        $this->_lineIndentation += 4 * $count;
    }

    public function unindent(int $count = 1)
    {
        $this->_lineIndentation -= 4 * $count;
    }

    public function line(int $count = 1)
    {
        return str_repeat(PHP_EOL, $count);
    }

    public function lines(...$args): Str
    {
        $string = '';
        $pad    = str_repeat(' ', $this->_lineIndentation);
        foreach ($args as $arg)
        {
            $string .= PHP_EOL . $pad. $arg ;
        }

        return $this->str($string);
    }

    public function str(string $string = null): Str
    {
        return (new Str($string));
    }
}
