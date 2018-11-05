<?php

namespace Stubborn\Ext;

use Stubborn\Lib\Json\Property;

trait Php70
{
    public function variableDoc(Property $property)
    {
        $type = $property->type;
        $name = $property->name();

        return $this->lines('/**', ' * var '. $type, ' */');
    }

    public function protectedDeclaration(Property $property)
    {
        $type = $property->type;
        $name = $property->name();
        $default = $property->default;

        return $this->lines("protected \$$name;") ;
    }

    public function propertyGetter(Property $property)
    {
        $output = $this->getterDoc($property);
        $output .= $this->publicGetter($property);

        return $this->str($output)->line();
    }

    public function propertySetter(Property $property)
    {
        $output = $this->setterDoc($property);
        $output .= $this->publicSetter($property);

        return $this->str($output)->line();
    }

    public function getterDoc(Property $property)
    {
        $cast = $this->phpTypeCast($property);

        $name = $property->name();

        return $this->lines('/**', " * @return $cast $name", " */");
    }

    public function setterDoc(Property $property)
    {
        $name = $property->name();

        return $this->lines('/**', " * @return \$this", " */");
    }

    public function publicGetter(Property $property)
    {
        $prefix = $property->type === 'boolean' ? 'is' : 'get';
        $fname = camel_case( $prefix . '_' . $property->nameToVariable);

        $output = $this->lines("public function $fname()", '{');
        $this->indent();
        $output .= $this->getterBody($property);
        $this->unindent();
        $output .= $this->lines('}');

        return $output;
    }

    public function getterBody(Property $property)
    {
        $name = $property->nameToVariable->camelCase;

        return $this->lines("return \$this->$name;");
    }

    public function publicSetter(Property $property)
    {
        $cast = $this->phpTypeCast($property);

        $name = $property->nameToVariable->camelCase;

        $output = $this->lines("public function set$name->ucFirst($cast \$$name)", '{');
        $this->indent();
        $output .= $this->setterBody($property);
        $this->unindent();
        $output .= $this->lines('}');

        return $output;
    }

    public function setterBody(Property $property)
    {
        return $this->lines("\$this->$property->nameStr = \$$property->ucFirst;", "return \$this;");
    }

    public function constantDeclaration(Property $property)
    {
        $constant = $property->nameToVariable->toUpper->padRight($this->propertyPadding());

        return $this->lines("const $constant = '$property->nameStr';");
    }

    public function phpTypeCast(Property $property)
    {
        $cast = 'string';

        foreach ($property->types() as $pType)
        {
            switch ($pType)
            {
                case 'object':
                    return 'array';
                case 'string':
                    return 'string';
                case 'integer':
                    return 'int';
                case 'boolean':
                    return 'bool';
                case 'null':
                    continue;
            }
        }

        return $cast;
    }
}
