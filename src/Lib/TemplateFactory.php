<?php

namespace Stubborn\Lib;

use Jenssegers\Blade\Blade;

class TemplateFactory
{
    protected static $templates;

    public static function blade(Config $config): Blade
    {
        if (empty(self::$templates['blade']))
        {
            self::$templates['blade'] = new Blade($config->templates()->path, $config->templates()->cache);
        }

        return self::$templates['blade'];
    }
}
