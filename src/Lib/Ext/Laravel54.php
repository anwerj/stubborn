<?php

namespace Stubborn\Ext;

use Stubborn\Lib\Json\Property;

trait Laravel54
{
    public function laravelValidatorProperty(Property $property)
    {
        $accessor = $this->laravelValidatorPropertyAccesor($property);

        $value    = $this->laravelValidatorPropertyRules($property);
;
        return $this->lines("$accessor => '$value',");
    }

    public function laravelValidatorPropertyAccesor(Property $property)
    {
        $padded = $property->nameStr->padRight($this->propertyPadding());

        return "\$this->$padded";
    }

    public function laravelValidatorPropertyRules(Property $property)
    {
        $output = [];

        $output[] = $this->laravelValidatorPropertyType($property);
        if ($property->pattern)
        {
            $output[] = 'regex:' . $property->pattern;
        }

        return implode('|', $output);
    }

    public function laravelValidatorPropertyType(Property $property)
    {
        $type = 'string';

        foreach ($property->types() as $pType)
        {
            switch ($pType)
            {
                case 'object':
                    return 'array';
                case 'null':
                    continue;
            }
        }

        return $type;
    }

    public function laravelMigrationProperty(Property $property, $index)
    {
        $method = $this->laravelMigrationPropertyMethod($property);
        $params = $this->laravelMigrationPropertyParams($property);

        $paramsList = implode(', ', $params);

        $output = $this->lines("\$table->$method($paramsList)");

        if ($index === 0)
        {
            $output .= $this->lines('      ->primary()');
        }

        if ($property->isNullable())
        {
            $output .= $this->lines('      ->nullable()');
        }

        return $output . ';' . $this->line();
    }

    public function laravelMigrationPropertyMethod(Property $property)
    {
        $method = 'string';

        foreach ($property->types() as $pType)
        {
            switch ($pType)
            {
                case 'object':
                    return 'text';
                case 'string':
                case 'integer':
                case 'boolean':
                    return $pType;
                case 'null':
                    continue;
            }
        }

        return $method;
    }

    public function laravelMigrationPropertyParams(Property $property)
    {
        $output = [$this->laravelMigrationPropertyAccesor($property)];

        switch ($this->laravelMigrationPropertyMethod($property))
        {
            case 'string':
                 $output[] = $this->laravelMigrationPropertyStringLength($property);
        }

        return $output;
    }

    public function laravelMigrationPropertyAccesor(Property $property)
    {
        return $this->title();
    }

    public function laravelMigrationPropertyStringLength(Property $property)
    {
        return $property->maxLength ?? 255;
    }

    public function laravelModelPropertyDefault(Property $property)
    {
        switch ($property->default)
        {
            case null:
                return 'null';
            case true:
                return 'true';
            case false:
                return 'false';
            default:
                return $property->default;
        }
    }

    public function laravelModelCastValue(Property $property)
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
