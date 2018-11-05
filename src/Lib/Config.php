<?php

namespace Stubborn\Lib;

class Config
{
    use LoadTrait;

    public function getStubId()
    {
        return str_replace('config.stubs.', '', $this->getId());
    }

    protected function getLoadId()
    {
        return 'config';
    }
}
