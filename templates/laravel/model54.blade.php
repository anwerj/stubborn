<?php
/**
 * @var $stub \Stubborn\Stubs\Laravel\Model54
 */
?>
{!! $stub->fileOpen() !!}

namespace {{$stub->namespace()}};
{{$stub->imports()}}

class {{$stub->className()}} extends Model{{$stub->indent()}}
{
    protected $table = '{{$stub->tableName()}}';
@foreach($stub->properties() as $name => $property)
    {{$stub->variableDoc($property)}}{{$stub->protectedDeclaration($property)}}
@endforeach

    {{ $stub->getterSeparator ()}}
@foreach($stub->properties() as $name => $property)
    {{$stub->getterDoc($property)}}{!! $stub->publicGetter($property) !!}
@endforeach

    {{$stub->setterSeparator()}}
@foreach($stub->properties() as $name => $property)
    {{$stub->setterDoc($property)}}{!! $stub->publicSetter($property) !!}
@endforeach
}
