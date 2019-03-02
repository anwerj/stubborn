<?php

namespace Stubborn\Lib;

use Stubborn\Ext;
use Stubborn\Lib\Json\Property;
use Stubborn\Stubs;

class Stub
{
    use Ext\Line;
    /**
     * @var Config
     */
    protected $base;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Schema
     */
    protected $schema;

    public function __construct(Config $config, Schema $schema, Config $base)
    {
        $this->config       = $config;
        $this->schema       = $schema;
        $this->base         = $base;
    }

    public function root()
    {
        return $this->schema->root;
    }

    /**
     * @return array
     */
    public function properties(): array
    {
        return $this->schema->root->properties();
    }

    public function generate()
    {
        $templatePath = $this->getTemplatePath();

        $template = TemplateFactory::blade($this->base);

        try
        {
            return $template->make($templatePath, ['stub' => $this]);
        }
        catch (\Throwable $e)
        {
            Error::report($e->getMessage(), []);
        }
    }

    public function getFilePath()
    {
        $chunks = explode('.', $this->config->getStubId());

        return self::getResolved($chunks, DIRECTORY_SEPARATOR);
    }

    public function getFileName()
    {
        return preg_replace('/((\w)+\\\)*/', '', static::class) . '.php';
    }

    public function propertyPadding(int $preFixLength = 0)
    {
        $maxLength = $this->schema->root->propertyMaxLength();

        $padding = intval(ceil(($maxLength+$preFixLength + 1)/4)) * 4;

        return $padding;
    }

    public function propertyIterator(string $method)
    {
        $index = 0;

        foreach ($this->properties() as $property)
        {
            if ($this->shouldIgnoreIteratingProperty($property, $method, $index))
            {
                continue;
            }
            $output[] = $this->{$method}($property, $index++);
        }

        return $this->lines(implode('', $output));
    }

    public function shouldIgnoreIteratingProperty(Property $property, string $method)
    {
        return false;
    }

    public static function resolveClassName(string $stubId)
    {
        $array = explode('.', $stubId);

        $class = Stub::getResolved([Stubs::class, Stub::getResolved($array)]);

        if (class_exists($class) === false)
        {
            Error::report('Stub class missing', [$class]);
        }

        return $class;
    }

    public static function getResolved(array $paths, $with = '\\')
    {
        $out = '';
        foreach ($paths as $path)
        {
            $out .= ucfirst(camel_case($path)) . $with;
        }

        return substr($out, 0, strlen($out) - 1);
    }

    private function getTemplatePath()
    {
        return $this->config->getStubId();
    }
}
